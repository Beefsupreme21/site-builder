<?php

use App\Actions\BlockPage\MoveBlock;
use App\Models\Site;
use Illuminate\Validation\ValidationException;

function pageWithThreeBlocks(): array
{
    $page = Site::factory()->create()->homePage();

    return [
        $page->blockPages()->create(['content' => '<p>One</p>', 'sort_order' => 1]),
        $page->blockPages()->create(['content' => '<p>Two</p>', 'sort_order' => 2]),
        $page->blockPages()->create(['content' => '<p>Three</p>', 'sort_order' => 3]),
    ];
}

test('moving up swaps sort order with the block above', function () {
    [$first, $second] = pageWithThreeBlocks();

    (new MoveBlock)->handle($second, ['direction' => 'up']);

    expect($second->fresh()->sort_order)->toBe(1);
    expect($first->fresh()->sort_order)->toBe(2);
});

test('moving down swaps sort order with the block below', function () {
    [$first, $second] = pageWithThreeBlocks();

    (new MoveBlock)->handle($first, ['direction' => 'down']);

    expect($first->fresh()->sort_order)->toBe(2);
    expect($second->fresh()->sort_order)->toBe(1);
});

test('moving the first block up leaves sort order unchanged', function () {
    [$first, $second, $third] = pageWithThreeBlocks();

    (new MoveBlock)->handle($first, ['direction' => 'up']);

    expect($first->fresh()->sort_order)->toBe(1);
    expect($second->fresh()->sort_order)->toBe(2);
    expect($third->fresh()->sort_order)->toBe(3);
});

test('moving the last block down leaves sort order unchanged', function () {
    [$first, $second, $third] = pageWithThreeBlocks();

    (new MoveBlock)->handle($third, ['direction' => 'down']);

    expect($first->fresh()->sort_order)->toBe(1);
    expect($second->fresh()->sort_order)->toBe(2);
    expect($third->fresh()->sort_order)->toBe(3);
});

test('does not swap with a block on another page', function () {
    [$first] = pageWithThreeBlocks();
    $otherPage = Site::factory()->create()->homePage();
    $stranger = $otherPage->blockPages()->create(['content' => '<p>Other</p>', 'sort_order' => 99]);

    (new MoveBlock)->handle($first, ['direction' => 'up']);

    expect($stranger->fresh()->sort_order)->toBe(99);
});

test('requires a direction', function () {
    [$first] = pageWithThreeBlocks();

    (new MoveBlock)->handle($first, []);
})->throws(ValidationException::class);

test('rejects a direction outside the enum', function () {
    [$first] = pageWithThreeBlocks();

    (new MoveBlock)->handle($first, ['direction' => 'sideways']);
})->throws(ValidationException::class);
