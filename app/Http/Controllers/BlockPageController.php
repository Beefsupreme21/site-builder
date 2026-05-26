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
        return Inertia::render('sites/pages/blocks/create', [
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

        return redirect()->route('sites.pages.edit', [$site, $page]);
    }

    public function destroy(Site $site, SitePage $page, BlockPage $blockPage): RedirectResponse
    {
        // Scoped route bindings ensure $blockPage already belongs to $page.
        $blockPage->delete();

        return redirect()->route('sites.pages.edit', [$site, $page]);
    }
}
