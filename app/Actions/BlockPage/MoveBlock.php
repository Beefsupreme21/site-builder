<?php

namespace App\Actions\BlockPage;

use App\Enums\BlockDirection;
use App\Models\BlockPage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MoveBlock
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function handle(BlockPage $block, array $input): void
    {
        $validated = Validator::make($input, [
            'direction' => ['required', Rule::enum(BlockDirection::class)],
        ])->validate();

        $direction = BlockDirection::from($validated['direction']);
        $siblings = $block->sitePage->blockPages();

        $neighbor = $direction === BlockDirection::Up
            ? $siblings->where('sort_order', '<', $block->sort_order)->orderByDesc('sort_order')->first()
            : $siblings->where('sort_order', '>', $block->sort_order)->orderBy('sort_order')->first();

        if ($neighbor === null) {
            return;
        }

        DB::transaction(function () use ($block, $neighbor): void {
            $currentOrder = $block->sort_order;

            $block->update(['sort_order' => $neighbor->sort_order]);
            $neighbor->update(['sort_order' => $currentOrder]);
        });
    }
}
