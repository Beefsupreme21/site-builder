<?php

namespace Database\Seeders;

use App\Models\Block;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\View;

class BlockSeeder extends Seeder
{
    /**
     * Seed the block library by rendering each Blade view in
     * resources/views/sites/blocks/ and saving its HTML as
     * `default_content`. Editing the Blade file + re-seeding refreshes
     * the library; existing block_pages are unaffected.
     */
    public function run(): void
    {
        $types = [
            'hero_centered',
            'hero_image',
            'content_simple',
            'content_split',
            'contact_form',
        ];

        foreach ($types as $type) {
            Block::updateOrCreate(
                ['type' => $type],
                ['default_content' => View::make("blocks.{$type}")->render()],
            );
        }
    }
}
