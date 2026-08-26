<?php

namespace App\Http\Controllers;

use App\Actions\Block\AddBlock;
use App\Actions\Block\RemoveBlock;
use App\Actions\Block\UpdateBlock;
use App\Enums\TemplateContext;
use App\Models\Block;
use App\Models\SitePage;
use App\Models\Template;
use App\Support\BlockCategories;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class BlockPageController extends Controller
{
    public function create(Request $request, SitePage $page): Response
    {
        $page->loadMissing('site');

        $category = $request->string('category')->toString();

        if ($category !== '' && ! BlockCategories::isValid($category)) {
            $category = '';
        }

        $counts = Template::query()
            ->where('context', TemplateContext::Page)
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $categories = collect(BlockCategories::all())
            ->map(fn (array $item) => [
                'slug' => $item['slug'],
                'name' => $item['name'],
                'group' => $item['group'],
                'count' => (int) ($counts[$item['slug']] ?? 0),
            ])
            ->values()
            ->all();

        $templatesQuery = Template::query()
            ->where('context', TemplateContext::Page)
            ->orderBy('id');

        if ($category !== '') {
            $templatesQuery->where('category', $category);
        }

        return inertia('blocks/create', [
            'site' => $page->site,
            'page' => $page,
            'layout' => null,
            'target' => 'page',
            'category' => $category !== '' ? $category : null,
            'activeCategory' => $category !== '' ? BlockCategories::find($category) : null,
            'categories' => $categories,
            'groups' => BlockCategories::groups(),
            'templates' => $category !== ''
                ? $templatesQuery
                    ->get(['id', 'type', 'name', 'category', 'default_content'])
                    ->all()
                : [],
        ]);
    }

    public function store(SitePage $page): RedirectResponse
    {
        $page->loadMissing('site');

        (new AddBlock)->handle($page, request()->all());

        return to_route('sites.pages.show', [$page->site, $page]);
    }

    public function edit(SitePage $page, Block $block): Response
    {
        $page->loadMissing('site');

        abort_if($block->isSlot(), 404);

        $block->load('template');

        return inertia('blocks/edit', [
            'site' => $page->site,
            'page' => $page,
            'layout' => null,
            'block' => $block,
            'target' => 'page',
            'provider' => config('ai.default'),
            'model' => config('ai.providers.'.config('ai.default').'.models.text.default'),
        ]);
    }

    public function update(SitePage $page, Block $block): RedirectResponse
    {
        $page->loadMissing('site');

        (new UpdateBlock)->handle($block, request()->all());

        return to_route('sites.pages.show', [$page->site, $page]);
    }

    public function destroy(SitePage $page, Block $block): RedirectResponse
    {
        $page->loadMissing('site');

        (new RemoveBlock)->handle($block);

        return to_route('sites.pages.show', [$page->site, $page]);
    }
}
