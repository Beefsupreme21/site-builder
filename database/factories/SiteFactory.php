<?php

namespace Database\Factories;

use App\Actions\Layout\CreateDefaultLayout;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Site>
 */
class SiteFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(2),
            'company_name' => fake()->company(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->companyEmail(),
            'logo' => null,
            'primary_color' => '#171717',
            'secondary_color' => '#525252',
        ];
    }

    /**
     * @return $this
     */
    public function configure(): self
    {
        return $this->afterCreating(function (Site $site): void {
            $layout = (new CreateDefaultLayout)->handle($site);

            $site->pages()->create([
                'slug' => 'home',
                'title' => $site->company_name,
                'order' => 0,
                'layout_id' => $layout->id,
            ]);
        });
    }
}
