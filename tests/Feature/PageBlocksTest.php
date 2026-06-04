<?php

use App\Models\Block;
use App\Models\Site;
use Database\Seeders\BlockSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(BlockSeeder::class);
});

test('block library is seeded with the starter blocks', function () {
    expect(Block::query()->orderBy('id')->pluck('type')->all())
        ->toBe([
            'hero_centered',
            'hero_image',
            'content_simple',
            'content_split',
            'contact_form',
        ]);
});

test('a block can be added to a page from the library', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = Block::query()->where('type', 'hero_centered')->firstOrFail();

    $this->post(route('sites.pages.blocks.store', [$site, $page]), [
        'block_id' => $block->id,
    ])->assertRedirect(route('sites.pages.show', [$site, $page]));

    expect($page->blockPages()->count())->toBe(1);
    expect($page->blockPages()->first()->content)
        ->toBe($block->default_content);
});

test('block_pages sort_order increments per add', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();
    $hero = Block::query()->where('type', 'hero_centered')->firstOrFail();
    $content = Block::query()->where('type', 'content_simple')->firstOrFail();

    $this->post(route('sites.pages.blocks.store', [$site, $page]), ['block_id' => $hero->id]);
    $this->post(route('sites.pages.blocks.store', [$site, $page]), ['block_id' => $content->id]);

    expect($page->blockPages()->orderBy('sort_order')->pluck('sort_order')->all())
        ->toBe([1, 2]);
});

test('preview renders the block content for a page', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = Block::query()->where('type', 'hero_centered')->firstOrFail();

    $page->blockPages()->create([
        'content' => '<p data-test-block>Hello from the hero block</p>',
        'sort_order' => 1,
    ]);

    $this->get(route('sites.preview', [$site, $page]))
        ->assertOk()
        ->assertSee('Hello from the hero block', false)
        ->assertSee('data-test-block', false)
        ->assertSee('@tailwindcss/browser@4', false);

    $page->blockPages()->create([
        'content' => $block->default_content,
        'sort_order' => 2,
    ]);

    $this->get(route('sites.preview', [$site, $page]))
        ->assertOk()
        ->assertSee('Welcome to your site', false);
});

test('a block can be removed from a page', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();
    $blockPage = $page->blockPages()->create([
        'content' => '<p>Removable</p>',
        'sort_order' => 1,
    ]);

    $this->from(route('sites.pages.show', [$site, $page]))
        ->delete(route('sites.pages.blocks.destroy', [$site, $page, $blockPage]))
        ->assertRedirect(route('sites.pages.show', [$site, $page]));

    expect($page->blockPages()->count())->toBe(0);
});

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
        ->patch(route('sites.pages.blocks.move', [$site, $page, $first, 'down']))
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
        ->patch(route('sites.pages.blocks.move', [$site, $page, $second, 'up']))
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
        ->patch(route('sites.pages.blocks.move', [$site, $page, $block, 'up']))
        ->assertRedirect(route('sites.pages.show', [$site, $page]));

    expect($block->fresh()->sort_order)->toBe(1);
});

test('removing a block scoped to the wrong page returns 404', function () {
    $site = Site::factory()->create();
    $pageA = $site->homePage();
    $pageB = $site->pages()->create([
        'slug' => 'other',
        'title' => 'Other',
        'sort_order' => 1,
    ]);
    $blockPage = $pageA->blockPages()->create([
        'content' => '<p>Belongs to pageA</p>',
        'sort_order' => 1,
    ]);

    $this->delete(route('sites.pages.blocks.destroy', [$site, $pageB, $blockPage]))
        ->assertNotFound();

    expect($pageA->blockPages()->count())->toBe(1);
});

test('block library picker page renders the available blocks', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();

    $this->get(route('sites.pages.blocks.create', [$site, $page]))
        ->assertOk()
        ->assertInertia(fn ($response) => $response
            ->component('blocks/create')
            ->has('blocks', 5)
            ->where('blocks.0.type', 'hero_centered'));
});
