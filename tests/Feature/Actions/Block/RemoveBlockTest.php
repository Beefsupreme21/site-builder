<?php

use App\Actions\Block\RemoveBlock;
use App\Models\Block;
use App\Models\Site;
use Illuminate\Validation\ValidationException;

test('removes a block from a page', function () {
    $page = Site::factory()->create()->homePage();
    $block = $page->blocks()->create([
        'content' => '<p>Hi</p>',
        'order' => 1,
    ]);

    (new RemoveBlock)->handle($block);

    expect(Block::whereKey($block->id)->exists())->toBeFalse();
});

test('does not remove the slot block from a layout', function () {
    $layout = Site::factory()->create()->defaultLayout();
    $slot = $layout->blocks()->whereHas('template', fn ($q) => $q->where('type', 'slot'))->firstOrFail();

    (new RemoveBlock)->handle($slot);
})->throws(ValidationException::class);

test('does not remove sibling blocks when deleting one block', function () {
    $page = Site::factory()->create()->homePage();
    $block = $page->blocks()->create(['content' => '<p>One</p>', 'order' => 1]);
    $page->blocks()->create(['content' => '<p>Two</p>', 'order' => 2]);

    (new RemoveBlock)->handle($block);

    expect($page->fresh()->blocks)->toHaveCount(1);
});
