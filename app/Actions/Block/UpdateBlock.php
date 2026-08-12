<?php

namespace App\Actions\Block;

use App\Models\Block;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UpdateBlock
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function handle(Block $block, array $input): Block
    {
        if ($block->isSlot()) {
            throw ValidationException::withMessages([
                'content' => ['The slot block cannot be edited.'],
            ]);
        }

        $validated = Validator::make($input, [
            'content' => ['required', 'string'],
        ])->validate();

        return DB::transaction(function () use ($block, $validated): Block {
            $block->update($validated);

            return $block;
        });
    }
}
