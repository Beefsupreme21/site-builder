<?php

namespace Database\Seeders;

use App\Enums\TemplateContext;
use App\Models\Template;
use App\Support\BlockTemplateView;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\View;

class TemplateSeeder extends Seeder
{
    /**
     * Seed the template library by rendering each Blade view in
     * resources/views/blocks/{category}/{type}.blade.php and saving
     * its HTML as `default_content`. Category folders match
     * `BlockCategories` slugs. Editing a Blade file + re-seeding
     * refreshes the library; existing blocks on pages are unaffected.
     */
    public function run(): void
    {
        $library = [
            ['type' => 'slot', 'context' => TemplateContext::System, 'category' => 'system', 'name' => 'Content Slot'],
            ['type' => 'nav_top', 'context' => TemplateContext::Layout, 'category' => 'element-headers', 'name' => 'Top Navigation'],
            ['type' => 'simple_footer', 'context' => TemplateContext::Layout, 'category' => 'footers', 'name' => 'Simple Footer'],
            ['type' => 'footer_social', 'context' => TemplateContext::Layout, 'category' => 'footers', 'name' => 'Footer with Social'],
            ['type' => 'hero_centered', 'context' => TemplateContext::Page, 'category' => 'hero', 'name' => 'Hero Centered'],
            ['type' => 'hero_image', 'context' => TemplateContext::Page, 'category' => 'hero', 'name' => 'Hero Image'],
            ['type' => 'split_screenshot', 'context' => TemplateContext::Page, 'category' => 'hero', 'name' => 'Split Screenshot'],
            ['type' => 'content_simple', 'context' => TemplateContext::Page, 'category' => 'content', 'name' => 'Simple Content'],
            ['type' => 'content_split', 'context' => TemplateContext::Page, 'category' => 'content', 'name' => 'Split Content'],
            ['type' => 'contact_form', 'context' => TemplateContext::Page, 'category' => 'contact', 'name' => 'Contact Form'],
            ['type' => 'newsletter_side_by_side', 'context' => TemplateContext::Page, 'category' => 'newsletter', 'name' => 'Newsletter Side by Side'],
            ['type' => 'newsletter_side_by_side_brand', 'context' => TemplateContext::Page, 'category' => 'newsletter', 'name' => 'Newsletter Side by Side (Brand)'],
            ['type' => 'newsletter_centered_card', 'context' => TemplateContext::Page, 'category' => 'newsletter', 'name' => 'Newsletter Centered Card'],
            ['type' => 'header_anchor', 'context' => TemplateContext::Layout, 'category' => 'element-headers', 'name' => 'Anchor Header'],
            ['type' => 'footer_local', 'context' => TemplateContext::Layout, 'category' => 'footers', 'name' => 'Local Business Footer'],
            ['type' => 'hero_local', 'context' => TemplateContext::Page, 'category' => 'hero', 'name' => 'Local Business Hero'],
            ['type' => 'services_cards', 'context' => TemplateContext::Page, 'category' => 'feature', 'name' => 'Service Cards'],
            ['type' => 'story_split', 'context' => TemplateContext::Page, 'category' => 'content', 'name' => 'Story Split'],
            ['type' => 'hours_location', 'context' => TemplateContext::Page, 'category' => 'contact', 'name' => 'Hours & Location'],
            ['type' => 'cta_banner', 'context' => TemplateContext::Page, 'category' => 'cta', 'name' => 'CTA Banner'],
            ['type' => 'header_practice', 'context' => TemplateContext::Layout, 'category' => 'element-headers', 'name' => 'Practice Header'],
            ['type' => 'footer_columns', 'context' => TemplateContext::Layout, 'category' => 'footers', 'name' => 'Footer Columns'],
            ['type' => 'hero_practice', 'context' => TemplateContext::Page, 'category' => 'hero', 'name' => 'Practice Hero'],
            ['type' => 'feature_reasons', 'context' => TemplateContext::Page, 'category' => 'feature', 'name' => 'Reasons Grid'],
            ['type' => 'testimonial_quote', 'context' => TemplateContext::Page, 'category' => 'testimonials', 'name' => 'Quote Cards'],
            ['type' => 'cta_book', 'context' => TemplateContext::Page, 'category' => 'cta', 'name' => 'CTA Booking'],
            ['type' => 'content_mission', 'context' => TemplateContext::Page, 'category' => 'content', 'name' => 'Mission Content'],
            ['type' => 'team_grid', 'context' => TemplateContext::Page, 'category' => 'team', 'name' => 'Team Grid'],
            ['type' => 'contact_split', 'context' => TemplateContext::Page, 'category' => 'contact', 'name' => 'Contact Split'],
            ['type' => 'header_studio', 'context' => TemplateContext::Layout, 'category' => 'element-headers', 'name' => 'Studio Header'],
            ['type' => 'footer_studio', 'context' => TemplateContext::Layout, 'category' => 'footers', 'name' => 'Studio Footer'],
            ['type' => 'hero_studio', 'context' => TemplateContext::Page, 'category' => 'hero', 'name' => 'Studio Hero'],
            ['type' => 'services_list', 'context' => TemplateContext::Page, 'category' => 'feature', 'name' => 'Numbered Services'],
            ['type' => 'services_detail', 'context' => TemplateContext::Page, 'category' => 'feature', 'name' => 'Service Detail Rows'],
            ['type' => 'stats_band', 'context' => TemplateContext::Page, 'category' => 'stats', 'name' => 'Stats Band'],
            ['type' => 'cta_quote', 'context' => TemplateContext::Page, 'category' => 'cta', 'name' => 'CTA Quote'],
            ['type' => 'contact_studio', 'context' => TemplateContext::Page, 'category' => 'contact', 'name' => 'Contact Studio'],
        ];

        foreach ($library as $template) {
            $view = BlockTemplateView::name($template['category'], $template['type']);

            if (! View::exists($view)) {
                throw new \RuntimeException("Block template view [{$view}] is missing.");
            }

            Template::updateOrCreate(
                ['type' => $template['type']],
                [
                    'context' => $template['context'],
                    'name' => $template['name'],
                    'category' => $template['category'],
                    'default_content' => BlockTemplateView::render($template['category'], $template['type']),
                ],
            );
        }
    }
}
