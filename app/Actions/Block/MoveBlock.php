<?php

namespace App\Actions\Block;

use App\Enums\BlockDirection;
use App\Models\Block;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MoveBlock
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function handle(Block $block, array $input): void
    {
        $validated = Validator::make($input, [
            'direction' => ['required', Rule::enum(BlockDirection::class)],
        ])->validate();

        $direction = BlockDirection::from($validated['direction']);
        $block->loadMissing('blockable');

        $blocks = $block->blockable->blocks()
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        $index = $blocks->search(fn (Block $candidate): bool => $candidate->is($block));

        if ($index === false) {
            return;
        }

        $targetIndex = $direction === BlockDirection::Up ? $index - 1 : $index + 1;

        if ($targetIndex < 0 || $targetIndex >= $blocks->count()) {
            return;
        }

        DB::transaction(function () use ($blocks, $index, $targetIndex): void {
            $items = $blocks->values()->all();

            [$items[$index], $items[$targetIndex]] = [$items[$targetIndex], $items[$index]];

            foreach ($items as $position => $item) {
                $item->update(['order' => $position]);
            }
        });
    }
}
