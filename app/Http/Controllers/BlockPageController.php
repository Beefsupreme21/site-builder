<?php

namespace App\Http\Controllers;

use App\Actions\BlockPage\AddBlock;
use App\Actions\BlockPage\RemoveBlock;
use App\Models\Block;
use App\Models\BlockPage;
use App\Models\SitePage;
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

        $counts = Block::query()
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

        $blocksQuery = Block::query()->orderBy('id');

        if ($category !== '') {
            $blocksQuery->where('category', $category);
        }

        return inertia('blocks/create', [
            'site' => $page->site,
            'page' => $page,
            'category' => $category !== '' ? $category : null,
            'activeCategory' => $category !== '' ? BlockCategories::find($category) : null,
            'categories' => $categories,
            'groups' => BlockCategories::groups(),
            'blocks' => $category !== ''
                ? $blocksQuery
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

    public function destroy(SitePage $page, BlockPage $block): RedirectResponse
    {
        $page->loadMissing('site');

        (new RemoveBlock)->handle($block);

        return to_route('sites.pages.show', [$page->site, $page]);
    }
}
