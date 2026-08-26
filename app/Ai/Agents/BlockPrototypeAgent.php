<?php

namespace App\Ai\Agents;

use App\Models\Block;
use App\Models\Site;
use App\Support\Ai\LocalSkill;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\MaxTokens;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

#[MaxTokens(16384)]
#[Timeout(180)]
class BlockPrototypeAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    private const VARIANT_COUNT = 4;

    /** @var array{instructions: string, picker: string, identifier: string} */
    private array $skill;

    public function __construct(
        public Site $site,
        public Block $block,
        public string $currentContent,
    ) {
        $this->block->loadMissing('template');
        $this->skill = LocalSkill::load('emilkowalski', 'prototype');
    }

    public function skillIdentifier(): string
    {
        return $this->skill['identifier'];
    }

    /**
     * @return list<string>
     */
    public function skillNames(): array
    {
        return [$this->skillIdentifier()];
    }

    public function instructions(): Stringable|string
    {
        $template = $this->block->template;

        $templateContext = $template !== null
            ? "Block template: {$template->name} (category: {$template->category}, type: {$template->type})."
            : 'Block template: custom HTML block.';

        $recon = <<<TEXT
            Site builder recon (Phase 2):
            - Stack: Laravel site builder, Tailwind CSS v4 via CDN, HTML block fragments.
            - Company: {$this->site->company_name}
            - Primary color: {$this->site->primary_color}
            - Secondary color: {$this->site->secondary_color}
            - Logo: {$this->site->logo}
            - Internal links use root-relative paths (href="/", href="/about").
            - {$templateContext}
            - Current block HTML (may be empty or a starter):
            {$this->currentContent}
            TEXT;

        $adaptation = <<<'TEXT'
            Adaptation for this product:
            - You are generating content for a site-builder block editor, NOT an isolated /prototypes route.
            - Return exactly 4 variants as structured output. Do not include picker markup in variant HTML — the app renders the picker separately using PICKER.md.
            - Each variant must be a complete HTML fragment ready to embed in a page preview.
            - Variant names must describe the direction (never "Option A/B/C").
            TEXT;

        return implode("\n\n", [
            $this->skill['instructions'],
            $recon,
            $adaptation,
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'variants' => $schema->array()
                ->min(self::VARIANT_COUNT)
                ->max(self::VARIANT_COUNT)
                ->items($schema->object(fn (JsonSchema $schema): array => [
                    'name' => $schema->string()->required(),
                    'axis' => $schema->string()->required(),
                    'html' => $schema->string()->required(),
                ]))
                ->required(),
        ];
    }
}
