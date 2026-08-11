<?php

namespace App\Actions\Site;

use App\Models\Site;
use Illuminate\Support\Facades\DB;

class DeleteSite
{
    public function handle(Site $site): void
    {
        DB::transaction(function () use ($site): void {
            $site->delete();
        });
    }
}
