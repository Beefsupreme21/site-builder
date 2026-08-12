<?php

namespace Database\Seeders;

use App\Enums\TemplateContext;
use App\Models\Template;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\View;

class TemplateSeeder extends Seeder
{
    /**
     * Seed the template library by rendering each Blade view in
     * resources/views/blocks/ and saving its HTML as
     * `default_content`. Editing the Blade file + re-seeding refreshes
     * the library; existing blocks on pages are unaffected.
     */
    public function run(): void
    {
        $library = [
            ['type' => 'slot', 'context' => TemplateContext::System, 'category' => 'system', 'name' => 'Content Slot'],
            ['type' => 'simple_footer', 'context' => TemplateContext::Layout, 'category' => 'footers', 'name' => 'Simple Footer'],
            ['type' => 'hero_centered', 'context' => TemplateContext::Page, 'category' => 'hero', 'name' => 'Hero Centered'],
            ['type' => 'hero_image', 'context' => TemplateContext::Page, 'category' => 'hero', 'name' => 'Hero Image'],
            ['type' => 'split_screenshot', 'context' => TemplateContext::Page, 'category' => 'hero', 'name' => 'Split Screenshot'],
            ['type' => 'content_simple', 'context' => TemplateContext::Page, 'category' => 'content', 'name' => 'Simple Content'],
            ['type' => 'content_split', 'context' => TemplateContext::Page, 'category' => 'content', 'name' => 'Split Content'],
            ['type' => 'contact_form', 'context' => TemplateContext::Page, 'category' => 'contact', 'name' => 'Contact Form'],
        ];

        foreach ($library as $template) {
            Template::updateOrCreate(
                ['type' => $template['type']],
                [
                    'context' => $template['context'],
                    'name' => $template['name'],
                    'category' => $template['category'],
                    'default_content' => View::make("blocks.{$template['type']}")->render(),
                ],
            );
        }
    }
}
