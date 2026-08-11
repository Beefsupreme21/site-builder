<?php

namespace Database\Seeders;

use App\Models\Block;
use App\Models\Site;
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

        $libraryBlocks = Block::query()->orderBy('id')->get();

        foreach ($sites as $attributes) {
            $site = Site::updateOrCreate(
                ['slug' => $attributes['slug']],
                $attributes,
            );

            $site->createDefaultHomePage();

            $home = $site->homePage();

            if ($home === null || $home->blockPages()->exists()) {
                continue;
            }

            foreach ($libraryBlocks as $i => $block) {
                $home->blockPages()->create([
                    'content' => $block->default_content,
                    'sort_order' => $i + 1,
                ]);
            }
        }
    }
}
