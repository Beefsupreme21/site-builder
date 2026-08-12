<?php

use App\Models\Site;
use App\Models\Template;

test('a block can move down and swap sort order with the block below', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();

    $first = $page->blocks()->create([
        'content' => '<p>First</p>',
        'order' => 0,
    ]);
    $second = $page->blocks()->create([
        'content' => '<p>Second</p>',
        'order' => 1,
    ]);

    $this->patch(route('blocks.move', [$first, 'down']))
        ->assertRedirect(route('sites.pages.show', [$site, $page]));

    expect($first->fresh()->order)->toBe(1);
    expect($second->fresh()->order)->toBe(0);
});

test('a block can move up and swap sort order with the block above', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();

    $first = $page->blocks()->create([
        'content' => '<p>First</p>',
        'order' => 0,
    ]);
    $second = $page->blocks()->create([
        'content' => '<p>Second</p>',
        'order' => 1,
    ]);

    $this->patch(route('blocks.move', [$second, 'up']))
        ->assertRedirect(route('sites.pages.show', [$site, $page]));

    expect($first->fresh()->order)->toBe(1);
    expect($second->fresh()->order)->toBe(0);
});

test('moving the first block up leaves sort order unchanged', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();

    $block = $page->blocks()->create([
        'content' => '<p>Only</p>',
        'order' => 1,
    ]);

    $this->patch(route('blocks.move', [$block, 'up']))
        ->assertRedirect(route('sites.pages.show', [$site, $page]));

    expect($block->fresh()->order)->toBe(1);
});

test('a direction outside the enum does not match the route', function () {
    $page = Site::factory()->create()->homePage();
    $block = $page->blocks()->create([
        'content' => '<p>Only</p>',
        'order' => 1,
    ]);

    $this->patch("/blocks/{$block->id}/move/sideways")->assertNotFound();
});

test('moving a layout block redirects back to the layout show page', function () {
    $site = Site::factory()->create();
    $layout = $site->defaultLayout();
    $footer = Template::query()->where('type', 'simple_footer')->firstOrFail();

    $block = $layout->blocks()->create([
        'template_id' => $footer->id,
        'content' => $footer->default_content,
        'order' => 1,
    ]);

    $this->patch(route('blocks.move', [$block, 'up']))
        ->assertRedirect(route('sites.layouts.show', [$site, $layout]));
});
