<?php

namespace App\Actions\BlockPage;

use App\Models\BlockPage;
use Illuminate\Support\Facades\DB;

class RemoveBlock
{
    public function handle(BlockPage $block): void
    {
        DB::transaction(function () use ($block): void {
            $block->delete();
        });
    }
}
