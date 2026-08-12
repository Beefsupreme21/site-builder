<?php

namespace App\Actions\Layout;

use App\Models\Layout;
use App\Models\Site;
use App\Models\Template;
use Illuminate\Support\Facades\DB;

class CreateDefaultLayout
{
    public function handle(Site $site, string $name = 'Default'): Layout
    {
        return DB::transaction(function () use ($site, $name): Layout {
            $layout = $site->layouts()->create([
                'name' => $name,
            ]);

            $slotTemplate = Template::query()->where('type', 'slot')->firstOrFail();

            $layout->blocks()->create([
                'template_id' => $slotTemplate->id,
                'content' => $slotTemplate->default_content,
                'order' => 0,
            ]);

            return $layout;
        });
    }
}
