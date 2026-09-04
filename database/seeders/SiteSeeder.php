<?php

namespace Database\Seeders;

use App\Models\Layout;
use App\Models\Site;
use App\Models\SitePage;
use App\Models\Template;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->starters() as $recipe) {
            $this->seedStarter($recipe);
        }
    }

    /**
     * @return list<array{
     *     slug: string,
     *     company_name: string,
     *     phone: string,
     *     email: string,
     *     logo: string,
     *     primary_color: string,
     *     secondary_color: string,
     *     layout: list<string>,
     *     pages: array<string, array{title: string, blocks: list<string>}>
     * }>
     */
    private function starters(): array
    {
        return [
            [
                'slug' => 'acme',
                'company_name' => 'Acme',
                'phone' => '(555) 123-4567',
                'email' => 'hello@acme.example',
                'logo' => 'https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600',
                'primary_color' => '#4F46E5',
                'secondary_color' => '#1E293B',
                'layout' => ['nav_top', 'slot', 'footer_social'],
                'pages' => [
                    'home' => [
                        'title' => 'Home',
                        'blocks' => ['split_screenshot', 'content_split'],
                    ],
                    'about' => [
                        'title' => 'About',
                        'blocks' => ['hero_centered', 'content_simple'],
                    ],
                    'contact' => [
                        'title' => 'Contact',
                        'blocks' => ['hero_centered', 'contact_form'],
                    ],
                ],
            ],
            [
                'slug' => 'ridgeline',
                'company_name' => 'Ridgeline Coffee',
                'phone' => '(503) 555-0148',
                'email' => 'hello@ridgelinecoffee.example',
                'logo' => '',
                'primary_color' => '#92400E',
                'secondary_color' => '#1C1917',
                'layout' => ['header_anchor', 'slot', 'footer_local'],
                'pages' => [
                    'home' => [
                        'title' => 'Ridgeline Coffee',
                        'blocks' => [
                            'hero_local',
                            'services_cards',
                            'story_split',
                            'hours_location',
                            'cta_banner',
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'fernwood',
                'company_name' => 'Fernwood Dental',
                'phone' => '(612) 555-0119',
                'email' => 'front.desk@fernwooddental.example',
                'logo' => '',
                'primary_color' => '#0F766E',
                'secondary_color' => '#0F172A',
                'layout' => ['header_practice', 'slot', 'footer_columns'],
                'pages' => [
                    'home' => [
                        'title' => 'Fernwood Dental',
                        'blocks' => [
                            'hero_practice',
                            'feature_reasons',
                            'testimonial_quote',
                            'cta_book',
                        ],
                    ],
                    'about' => [
                        'title' => 'About',
                        'blocks' => ['content_mission', 'team_grid', 'cta_book'],
                    ],
                    'contact' => [
                        'title' => 'Contact',
                        'blocks' => ['contact_split'],
                    ],
                ],
            ],
            [
                'slug' => 'alder-and-vine',
                'company_name' => 'Alder & Vine',
                'phone' => '(207) 555-0132',
                'email' => 'studio@alderandvine.example',
                'logo' => '',
                'primary_color' => '#065F46',
                'secondary_color' => '#18181B',
                'layout' => ['header_studio', 'slot', 'footer_studio'],
                'pages' => [
                    'home' => [
                        'title' => 'Alder & Vine',
                        'blocks' => [
                            'hero_studio',
                            'services_list',
                            'stats_band',
                            'cta_quote',
                        ],
                    ],
                    'services' => [
                        'title' => 'Services',
                        'blocks' => ['services_detail', 'cta_quote'],
                    ],
                    'contact' => [
                        'title' => 'Contact',
                        'blocks' => ['contact_studio'],
                    ],
                ],
            ],
            [
                'slug' => 'northwind',
                'company_name' => 'Northwind Studio',
                'phone' => '(555) 987-6543',
                'email' => 'hello@northwindstudio.example',
                'logo' => 'https://images.unsplash.com/photo-1611224923853-80b023f02d71?auto=format&fit=crop&w=176&h=44&q=80',
                'primary_color' => '#DC2626',
                'secondary_color' => '#171717',
                'layout' => ['slot'],
                'pages' => [
                    'home' => [
                        'title' => 'Northwind Studio',
                        'blocks' => ['hero_image', 'content_simple', 'contact_form'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param  array{
     *     slug: string,
     *     company_name: string,
     *     phone: string,
     *     email: string,
     *     logo: string,
     *     primary_color: string,
     *     secondary_color: string,
     *     layout: list<string>,
     *     pages: array<string, array{title: string, blocks: list<string>}>
     * }  $recipe
     */
    private function seedStarter(array $recipe): void
    {
        $site = Site::updateOrCreate(
            ['slug' => $recipe['slug']],
            [
                'company_name' => $recipe['company_name'],
                'phone' => $recipe['phone'],
                'email' => $recipe['email'],
                'logo' => $recipe['logo'],
                'primary_color' => $recipe['primary_color'],
                'secondary_color' => $recipe['secondary_color'],
            ],
        );

        if ($site->pages()->exists()) {
            return;
        }

        $layout = $this->seedLayout($site, $recipe['layout']);

        $order = 0;

        foreach ($recipe['pages'] as $slug => $pageRecipe) {
            $page = $site->pages()->create([
                'slug' => $slug,
                'title' => $pageRecipe['title'],
                'order' => $order,
                'layout_id' => $layout->id,
            ]);

            $this->seedPageBlocks($page, $pageRecipe['blocks']);

            $order++;
        }
    }

    /**
     * @param  list<string>  $blockTypes
     */
    private function seedLayout(Site $site, array $blockTypes): Layout
    {
        $layout = $site->layouts()->create([
            'name' => 'Default',
        ]);

        foreach ($blockTypes as $index => $type) {
            $template = Template::query()->where('type', $type)->firstOrFail();

            $layout->blocks()->create([
                'template_id' => $template->id,
                'content' => $template->default_content,
                'order' => $index,
            ]);
        }

        return $layout;
    }

    /**
     * @param  list<string>  $blockTypes
     */
    private function seedPageBlocks(SitePage $page, array $blockTypes): void
    {
        foreach ($blockTypes as $index => $type) {
            $template = Template::query()->where('type', $type)->firstOrFail();

            $page->blocks()->create([
                'template_id' => $template->id,
                'content' => $template->default_content,
                'order' => $index + 1,
            ]);
        }
    }
}
