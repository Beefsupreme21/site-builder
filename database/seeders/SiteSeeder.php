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
                        'blocks' => ['contact_split', 'faqs_accordion'],
                    ],
                ],
            ],
            [
                'slug' => 'willow',
                'company_name' => 'Willow',
                'phone' => '(207) 555-0132',
                'email' => 'studio@willow.example',
                'logo' => '',
                'primary_color' => '#065F46',
                'secondary_color' => '#18181B',
                'layout' => ['header_studio', 'slot', 'footer_studio'],
                'pages' => [
                    'home' => [
                        'title' => 'Willow',
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
            $this->syncStarterBlocks($site);

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
    private function syncStarterBlocks(Site $site): void
    {
        $site->load('layouts.blocks.template', 'pages.blocks.template');

        foreach ($site->layouts as $layout) {
            foreach ($layout->blocks as $block) {
                if ($block->template !== null) {
                    $block->update(['content' => $block->template->default_content]);
                }
            }
        }

        foreach ($site->pages as $page) {
            foreach ($page->blocks as $block) {
                if ($block->template !== null) {
                    $block->update(['content' => $block->template->default_content]);
                }
            }
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
