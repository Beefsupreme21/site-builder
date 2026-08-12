<?php

namespace App\Http\Controllers;

use App\Actions\Block\MoveBlock;
use App\Models\Block;
use App\Models\Layout;
use App\Models\SitePage;
use Illuminate\Http\RedirectResponse;

class BlockPageMoveController extends Controller
{
    public function __invoke(Block $block, string $direction): RedirectResponse
    {
        (new MoveBlock)->handle($block, ['direction' => $direction]);

        $blockable = $block->blockable;

        if ($blockable instanceof SitePage) {
            $blockable->load('site');

            return to_route('sites.pages.show', [$blockable->site, $blockable]);
        }

        if ($blockable instanceof Layout) {
            $blockable->load('site');

            return to_route('sites.layouts.show', [$blockable->site, $blockable]);
        }

        abort(404);
    }
}
