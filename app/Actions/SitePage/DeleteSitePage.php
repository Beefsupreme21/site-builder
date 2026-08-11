<?php

namespace App\Actions\SitePage;

use App\Models\SitePage;
use Illuminate\Support\Facades\DB;

class DeleteSitePage
{
    public function handle(SitePage $page): void
    {
        DB::transaction(function () use ($page): void {
            $page->delete();
        });
    }
}
