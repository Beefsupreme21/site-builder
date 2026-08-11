<?php

namespace App\Http\Controllers;

use App\Actions\BlockPage\MoveBlock;
use App\Models\BlockPage;
use Illuminate\Http\RedirectResponse;

class BlockPageMoveController extends Controller
{
    public function __invoke(BlockPage $block, string $direction): RedirectResponse
    {
        (new MoveBlock)->handle($block, ['direction' => $direction]);

        $page = $block->sitePage()->with('site')->first();

        return to_route('sites.pages.show', [$page->site, $page]);
    }
}
