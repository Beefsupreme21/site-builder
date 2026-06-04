<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\BlockPage;
use App\Models\Site;
use App\Models\SitePage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class BlockPageController extends Controller
{
    public function create(Site $site, SitePage $page): Response
    {
        return Inertia::render('blocks/create', [
            'site' => $site,
            'page' => $page,
            'blocks' => Block::query()
                ->orderBy('id')
                ->get()
                ->map(fn (Block $block) => [
                    'id' => $block->id,
                    'type' => $block->type,
                    'name' => $block->displayName(),
                    'default_content' => $block->default_content,
                ]),
        ]);
    }

    public function store(Request $request, Site $site, SitePage $page): RedirectResponse
    {
        $data = $request->validate([
            'block_id' => ['required', 'integer', Rule::exists('blocks', 'id')],
        ]);

        $block = Block::findOrFail($data['block_id']);

        $page->blockPages()->create([
            'content' => $block->default_content,
            'sort_order' => (int) $page->blockPages()->max('sort_order') + 1,
        ]);

        return redirect()->route('sites.pages.show', [$site, $page]);
    }

    public function destroy(Site $site, SitePage $page, BlockPage $blockPage): RedirectResponse
    {
        $blockPage->delete();

        return redirect()->back();
    }

    public function move(Site $site, SitePage $page, BlockPage $blockPage, string $direction): RedirectResponse
    {
        $neighbor = $direction === 'up'
            ? $page->blockPages()
                ->where('sort_order', '<', $blockPage->sort_order)
                ->orderByDesc('sort_order')
                ->first()
            : $page->blockPages()
                ->where('sort_order', '>', $blockPage->sort_order)
                ->orderBy('sort_order')
                ->first();

        if ($neighbor !== null) {
            $currentOrder = $blockPage->sort_order;
            $blockPage->update(['sort_order' => $neighbor->sort_order]);
            $neighbor->update(['sort_order' => $currentOrder]);
        }

        return redirect()->back();
    }
}
