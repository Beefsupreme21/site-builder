<?php

namespace App\Ai\Agents;

use App\Models\Block;
use App\Models\Site;
use App\Support\Ai\LocalSkill;
use App\Support\Ai\SkillRegistry;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\MaxTokens;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

#[MaxTokens(8192)]
#[Timeout(120)]
class BlockVariantAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    /** @var array{instructions: string, picker: string, identifier: string, bundled_identifiers: list<string>} */
    private array $loadedSkill;

    public function __construct(
        public Site $site,
        public Block $block,
        public string $currentContent,
        public string $skillKey,
        public string $variantName,
        public string $variantAxis,
        public int $variantIndex,
        public int $variantCount,
    ) {
        $this->block->loadMissing('template');
        $this->loadedSkill = LocalSkill::loadWithBundles($this->skillKey);
    }

    /**
     * @return list<string>
     */
    public function skillNames(): array
    {
        return SkillRegistry::skillIdentifiersFor($this->skillKey);
    }

    public function instructions(): Stringable|string
    {
        $template = $this->block->template;

        $templateContext = $template !== null
            ? "Block template: {$template->name} (category: {$template->category}, type: {$template->type})."
            : 'Block template: custom HTML block.';

        $n = $this->variantIndex + 1;
        $total = $this->variantCount;

        $recon = <<<TEXT
            Site builder recon:
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

        $adaptation = $total === 1
            ? <<<'TEXT'
            Adaptation for this product — single block generation:
            - Follow the design skill instructions below strictly.
            - Return a complete HTML fragment ready to embed in a page preview.
            - Do not include picker markup — the app renders UI separately.
            - Use Tailwind CSS v4 utility classes. Real copy, no lorem ipsum.
            TEXT
            : <<<TEXT
            Adaptation for this product — build ONE variant only:
            - You are generating variant {$n} of {$total}: "{$this->variantName}" (axis: {$this->variantAxis}).
            - Follow the design skill instructions below strictly — this variant must reflect that skill's philosophy.
            - Return a complete HTML fragment ready to embed in a page preview.
            - Do not include picker markup — the app renders the picker separately.
            - Use Tailwind CSS v4 utility classes. Real copy, no lorem ipsum.
            TEXT;

        return implode("\n\n", [
            $this->loadedSkill['instructions'],
            $recon,
            $adaptation,
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'html' => $schema->string()->required(),
        ];
    }
}
