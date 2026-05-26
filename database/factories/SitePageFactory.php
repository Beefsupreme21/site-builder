<?php

namespace Database\Factories;

use App\Models\Site;
use App\Models\SitePage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SitePage>
 */
class SitePageFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->words(2, true);

        return [
            'site_id' => Site::factory(),
            'slug' => Str::slug($title),
            'title' => ucwords($title),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
