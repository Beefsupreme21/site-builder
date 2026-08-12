<?php

use App\Actions\Block\UpdateBlock;
use App\Models\Site;
use Illuminate\Validation\ValidationException;

test('updates the block content', function () {
    $page = Site::factory()->create()->homePage();
    $block = $page->blocks()->create([
        'content' => '<p>Before</p>',
        'order' => 1,
    ]);

    (new UpdateBlock)->handle($block, ['content' => '<p>After</p>']);

    expect($block->fresh()->content)->toBe('<p>After</p>');
});

test('does not update the slot block', function () {
    $slot = Site::factory()->create()->defaultLayout()->blocks()
        ->whereHas('template', fn ($q) => $q->where('type', 'slot'))
        ->firstOrFail();

    (new UpdateBlock)->handle($slot, ['content' => '<p>Nope</p>']);
})->throws(ValidationException::class);

test('requires content', function () {
    $block = Site::factory()->create()->homePage()->blocks()->create([
        'content' => '<p>Hi</p>',
        'order' => 1,
    ]);

    (new UpdateBlock)->handle($block, ['content' => '']);
})->throws(ValidationException::class);

test('does not update a block when validation fails', function () {
    $block = Site::factory()->create()->homePage()->blocks()->create([
        'content' => '<p>Hi</p>',
        'order' => 1,
    ]);

    try {
        (new UpdateBlock)->handle($block, ['content' => '']);
    } catch (ValidationException) {
        // expected
    }

    expect($block->fresh()->content)->toBe('<p>Hi</p>');
});
