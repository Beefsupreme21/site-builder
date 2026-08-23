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
