<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sites = [
            [
                'slug' => 'acme-hardware',
                'template' => 'default',
                'company_name' => 'Acme Hardware Co.',
                'phone' => '(555) 123-4567',
                'email' => 'hello@acmehardware.example',
                'logo' => 'images/seeds/acme-hardware.svg',
            ],
            [
                'slug' => 'blue-ocean-dental',
                'template' => 'alternate',
                'company_name' => 'Blue Ocean Dental',
                'phone' => '(555) 234-5678',
                'email' => 'appointments@blueoceandental.example',
                'logo' => 'images/seeds/blue-ocean-dental.svg',
            ],
            [
                'slug' => 'northside-cafe',
                'template' => 'alternate',
                'company_name' => 'Northside Café',
                'phone' => '(555) 345-6789',
                'email' => 'info@northsidecafe.example',
                'logo' => 'images/seeds/northside-cafe.svg',
            ],
            [
                'slug' => 'summit-fitness',
                'template' => 'default',
                'company_name' => 'Summit Fitness Studio',
                'phone' => '(555) 456-7890',
                'email' => 'train@summitfitness.example',
                'logo' => 'images/seeds/summit-fitness.svg',
            ],
        ];

        foreach ($sites as $attributes) {
            Site::updateOrCreate(
                ['slug' => $attributes['slug']],
                $attributes,
            );
        }
    }
}
