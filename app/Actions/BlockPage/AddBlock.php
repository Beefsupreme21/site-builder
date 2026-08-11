<?php

namespace App\Actions\BlockPage;

use App\Models\Block;
use App\Models\BlockPage;
use App\Models\SitePage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AddBlock
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function handle(SitePage $page, array $input): BlockPage
    {
        $validated = Validator::make($input, [
            'block_id' => ['required', 'integer', Rule::exists('blocks', 'id')],
        ])->validate();

        return DB::transaction(function () use ($page, $validated): BlockPage {
            $block = Block::findOrFail($validated['block_id']);

            return $page->blockPages()->create([
                'content' => $block->default_content,
                'sort_order' => (int) $page->blockPages()->max('sort_order') + 1,
            ]);
        });
    }
}
