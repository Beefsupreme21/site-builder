<?php

namespace Database\Seeders;

use App\Actions\Layout\CreateDefaultLayout;
use App\Enums\TemplateContext;
use App\Models\Site;
use App\Models\Template;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        $sites = [
            [
                'slug' => 'acme-hardware',
                'company_name' => 'Acme Hardware Co.',
                'phone' => '(555) 123-4567',
                'email' => 'hello@acmehardware.example',
                'logo' => 'https://cdn.jsdelivr.net/npm/simple-icons@14.6.0/icons/ikea.svg',
                'primary_color' => '#B45309',
                'secondary_color' => '#44403C',
            ],
            [
                'slug' => 'blue-ocean-dental',
                'company_name' => 'Blue Ocean Dental',
                'phone' => '(555) 234-5678',
                'email' => 'appointments@blueoceandental.example',
                'logo' => 'https://cdn.simpleicons.org/abbott',
                'primary_color' => '#0284C7',
                'secondary_color' => '#0F766E',
            ],
            [
                'slug' => 'northside-cafe',
                'company_name' => 'Northside Café',
                'phone' => '(555) 345-6789',
                'email' => 'info@northsidecafe.example',
                'logo' => 'https://cdn.jsdelivr.net/npm/simple-icons@14.6.0/icons/starbucks.svg',
                'primary_color' => '#92400E',
                'secondary_color' => '#CA8A04',
            ],
            [
                'slug' => 'summit-fitness',
                'company_name' => 'Summit Fitness Studio',
                'phone' => '(555) 456-7890',
                'email' => 'train@summitfitness.example',
                'logo' => 'https://cdn.jsdelivr.net/npm/simple-icons@14.6.0/icons/nike.svg',
                'primary_color' => '#DC2626',
                'secondary_color' => '#171717',
            ],
        ];

        $pageTemplates = Template::query()
            ->where('context', TemplateContext::Page)
            ->orderBy('id')
            ->get();

        foreach ($sites as $attributes) {
            $site = Site::updateOrCreate(
                ['slug' => $attributes['slug']],
                $attributes,
            );

            $layout = $site->defaultLayout() ?? (new CreateDefaultLayout)->handle($site);

            $home = $site->homePage() ?? $site->pages()->create([
                'slug' => 'home',
                'title' => $site->company_name,
                'order' => 0,
                'layout_id' => $layout->id,
            ]);

            if ($home->blocks()->exists()) {
                continue;
            }

            foreach ($pageTemplates as $i => $template) {
                $home->blocks()->create([
                    'template_id' => $template->id,
                    'content' => $template->default_content,
                    'order' => $i + 1,
                ]);
            }
        }
    }
}
