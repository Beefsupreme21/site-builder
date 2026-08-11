<?php

use App\Actions\BlockPage\AddBlock;
use App\Models\Block;
use App\Models\BlockPage;
use App\Models\Site;
use Illuminate\Validation\ValidationException;

test('adds a block to the page using the library default content', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = Block::create([
        'name' => 'Hero',
        'category' => 'hero',
        'type' => 'hero',
        'default_content' => '<section>Hero</section>',
    ]);

    $blockPage = (new AddBlock)->handle($page, ['block_id' => $block->id]);

    expect($blockPage->content)->toBe('<section>Hero</section>');
    expect($blockPage->site_page_id)->toBe($page->id);
});

test('appends each block after the current highest sort order', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = Block::create([
        'name' => 'Hero',
        'category' => 'hero',
        'type' => 'hero',
        'default_content' => '<section>Hero</section>',
    ]);

    $first = (new AddBlock)->handle($page, ['block_id' => $block->id]);
    $second = (new AddBlock)->handle($page, ['block_id' => $block->id]);

    expect($first->sort_order)->toBe(1);
    expect($second->sort_order)->toBe(2);
});

test('requires a block id', function () {
    (new AddBlock)->handle(Site::factory()->create()->homePage(), []);
})->throws(ValidationException::class);

test('requires the block to exist in the library', function () {
    (new AddBlock)->handle(Site::factory()->create()->homePage(), ['block_id' => 9999]);
})->throws(ValidationException::class);

test('does not add a block when validation fails', function () {
    $page = Site::factory()->create()->homePage();

    try {
        (new AddBlock)->handle($page, ['block_id' => 9999]);
    } catch (ValidationException) {
        // expected
    }

    expect(BlockPage::count())->toBe(0);
});
