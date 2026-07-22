<?php

namespace App\Http\Controllers;

use App\Models\BlockPage;
use Illuminate\Http\RedirectResponse;

class BlockPageMoveController extends Controller
{
    public function update(BlockPage $block, string $direction): RedirectResponse
    {
        $page = $block->sitePage;

        $neighbor = $direction === 'up'
            ? $page->blockPages()
                ->where('sort_order', '<', $block->sort_order)
                ->orderByDesc('sort_order')
                ->first()
            : $page->blockPages()
                ->where('sort_order', '>', $block->sort_order)
                ->orderBy('sort_order')
                ->first();

        if ($neighbor !== null) {
            $currentOrder = $block->sort_order;
            $block->update(['sort_order' => $neighbor->sort_order]);
            $neighbor->update(['sort_order' => $currentOrder]);
        }

        return redirect()->back();
    }
}
