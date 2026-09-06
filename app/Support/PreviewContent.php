<?php

namespace App\Support;

use App\Enums\TemplateContext;
use App\Models\Block;
use App\Models\Site;
use App\Models\SitePage;
use Illuminate\Support\Collection;

/**
 * Rewrites site-root links in block HTML during preview only.
 *
 * Blocks should store the URLs customers want when live, e.g. href="/about".
 * Preview prefixes those paths so they work under /preview/{site}/….
 * Layout blocks are re-rendered from their Blade view so branding like the
 * logo always reflects the current site settings.
 */
class PreviewContent
{
    /**
     * @param  Collection<string, SitePage>  $pages
     */
    public function __construct(
        private Site $site,
        private Collection $pages,
    ) {}

    public static function for(Site $site): self
    {
        $site->loadMissing('pages');

        return new self($site, $site->pages->keyBy('slug'));
    }

    public function renderBlock(Block $block): string
    {
        $block->loadMissing('template');

        $content = $block->template !== null
            && (
                $block->template->context === TemplateContext::Layout
                || $block->content === $block->template->default_content
            )
            ? BlockTemplateView::render(
                $block->template->category,
                $block->template->type,
                $this->site,
            )
            : $block->content;

        return $this->render($content);
    }

    public function render(string $content): string
    {
        return preg_replace_callback(
            '/href="(\/(?:[a-z0-9-]+)?)"/',
            fn (array $matches): string => 'href="'.$this->previewUrlForPath($matches[1]).'"',
            $content,
        );
    }

    private function previewUrlForPath(string $path): string
    {
        if ($path === '/') {
            $page = $this->homePage();

            return $page !== null
                ? route('preview.show', [$this->site, $page])
                : route('preview.index', $this->site);
        }

        $slug = ltrim($path, '/');
        $page = $this->pages->get($slug);

        if ($page === null) {
            return $path;
        }

        return route('preview.show', [$this->site, $page]);
    }

    private function homePage(): ?SitePage
    {
        return $this->pages->get('home') ?? $this->pages->sortBy('order')->first();
    }
}
