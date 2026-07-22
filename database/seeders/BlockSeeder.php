<?php

namespace Database\Seeders;

use App\Models\Block;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\View;

class BlockSeeder extends Seeder
{
    /**
     * Seed the block library by rendering each Blade view in
     * resources/views/blocks/ and saving its HTML as
     * `default_content`. Editing the Blade file + re-seeding refreshes
     * the library; existing block_pages are unaffected.
     */
    public function run(): void
    {
        $library = [
            ['type' => 'hero_centered', 'category' => 'hero', 'name' => 'Hero Centered'],
            ['type' => 'hero_image', 'category' => 'hero', 'name' => 'Hero Image'],
            ['type' => 'split_screenshot', 'category' => 'hero', 'name' => 'Split Screenshot'],
            ['type' => 'content_simple', 'category' => 'content', 'name' => 'Simple Content'],
            ['type' => 'content_split', 'category' => 'content', 'name' => 'Split Content'],
            ['type' => 'contact_form', 'category' => 'contact', 'name' => 'Contact Form'],
        ];

        foreach ($library as $block) {
            Block::updateOrCreate(
                ['type' => $block['type']],
                [
                    'name' => $block['name'],
                    'category' => $block['category'],
                    'default_content' => View::make("blocks.{$block['type']}")->render(),
                ],
            );
        }
    }
}
