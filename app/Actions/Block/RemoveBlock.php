<?php

namespace App\Actions\Block;

use App\Models\Block;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RemoveBlock
{
    public function handle(Block $block): void
    {
        if ($block->isSlot()) {
            throw ValidationException::withMessages([
                'block' => ['The slot block cannot be removed.'],
            ]);
        }

        DB::transaction(function () use ($block): void {
            $block->delete();
        });
    }
}
