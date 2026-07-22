<?php

use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a block can move down and swap sort order with the block below', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();

    $first = $page->blockPages()->create([
        'content' => '<p>First</p>',
        'sort_order' => 1,
    ]);
    $second = $page->blockPages()->create([
        'content' => '<p>Second</p>',
        'sort_order' => 2,
    ]);

    $this->from(route('sites.pages.show', [$site, $page]))
        ->patch(route('blocks.move', [$first, 'down']))
        ->assertRedirect(route('sites.pages.show', [$site, $page]));

    expect($first->fresh()->sort_order)->toBe(2);
    expect($second->fresh()->sort_order)->toBe(1);
});

test('a block can move up and swap sort order with the block above', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();

    $first = $page->blockPages()->create([
        'content' => '<p>First</p>',
        'sort_order' => 1,
    ]);
    $second = $page->blockPages()->create([
        'content' => '<p>Second</p>',
        'sort_order' => 2,
    ]);

    $this->from(route('sites.pages.show', [$site, $page]))
        ->patch(route('blocks.move', [$second, 'up']))
        ->assertRedirect(route('sites.pages.show', [$site, $page]));

    expect($first->fresh()->sort_order)->toBe(2);
    expect($second->fresh()->sort_order)->toBe(1);
});

test('moving the first block up leaves sort order unchanged', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();

    $block = $page->blockPages()->create([
        'content' => '<p>Only</p>',
        'sort_order' => 1,
    ]);

    $this->from(route('sites.pages.show', [$site, $page]))
        ->patch(route('blocks.move', [$block, 'up']))
        ->assertRedirect(route('sites.pages.show', [$site, $page]));

    expect($block->fresh()->sort_order)->toBe(1);
});
