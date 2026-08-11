<?php

use App\Actions\BlockPage\RemoveBlock;
use App\Models\BlockPage;
use App\Models\Site;

test('removes the block from the page', function () {
    $page = Site::factory()->create()->homePage();
    $block = $page->blockPages()->create(['content' => '<p>Hi</p>', 'sort_order' => 1]);

    (new RemoveBlock)->handle($block);

    expect(BlockPage::whereKey($block->id)->exists())->toBeFalse();
});

test('leaves the other blocks on the page alone', function () {
    $page = Site::factory()->create()->homePage();
    $block = $page->blockPages()->create(['content' => '<p>One</p>', 'sort_order' => 1]);
    $page->blockPages()->create(['content' => '<p>Two</p>', 'sort_order' => 2]);

    (new RemoveBlock)->handle($block);

    expect($page->fresh()->blockPages)->toHaveCount(1);
});
