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

#[MaxTokens(8192)]
#[Timeout(120)]
class BlockHtmlAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    /** @var array{instructions: string, identifier: string} */
    private array $loadedSkill;

    public function __construct(
        public Site $site,
        public Block $block,
        public string $currentContent,
    ) {
        $this->block->loadMissing('template');
        $this->loadedSkill = LocalSkill::forBlockEditor();
    }

    public function skillIdentifier(): string
    {
        return $this->loadedSkill['identifier'];
    }

    public function instructions(): Stringable|string
    {
        $template = $this->block->template;

        $templateContext = $template !== null
            ? "Block template: {$template->name} (category: {$template->category}, type: {$template->type})."
            : 'Block template: custom HTML block.';

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

        $adaptation = <<<'TEXT'
            Adaptation for this product:
            - Follow the design skill instructions below strictly.
            - Return a complete HTML fragment ready to embed in a page preview.
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
