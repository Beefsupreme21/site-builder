<?php

use App\Models\Block;
use App\Models\Site;
use Database\Seeders\BlockSeeder;

beforeEach(function (): void {
    $this->seed(BlockSeeder::class);
});

test('block library is seeded with the starter blocks', function () {
    expect(Block::query()->orderBy('id')->pluck('type')->all())
        ->toBe([
            'hero_centered',
            'hero_image',
            'split_screenshot',
            'content_simple',
            'content_split',
            'contact_form',
        ]);
});

test('a block can be added to a page from the library', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = Block::query()->where('type', 'hero_centered')->firstOrFail();

    $this->post(route('pages.blocks.store', $page), [
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

    $this->post(route('pages.blocks.store', $page), ['block_id' => $hero->id]);
    $this->post(route('pages.blocks.store', $page), ['block_id' => $content->id]);

    expect($page->blockPages()->orderBy('sort_order')->pluck('sort_order')->all())
        ->toBe([1, 2]);
});

test('a block can be removed from a page', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();
    $blockPage = $page->blockPages()->create([
        'content' => '<p>Removable</p>',
        'sort_order' => 1,
    ]);

    $this->delete(route('pages.blocks.destroy', [$page, $blockPage]))
        ->assertRedirect(route('sites.pages.show', [$site, $page]));

    expect($page->blockPages()->count())->toBe(0);
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

    $this->delete(route('pages.blocks.destroy', [$pageB, $blockPage]))
        ->assertNotFound();

    expect($pageA->blockPages()->count())->toBe(1);
});

test('block library picker shows section categories by default', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();

    $this->get(route('pages.blocks.create', $page))
        ->assertOk()
        ->assertInertia(fn ($response) => $response
            ->component('blocks/create')
            ->where('category', null)
            ->has('categories', 19)
            ->has('groups', 2)
            ->where('categories.0.slug', 'hero')
            ->where('categories.0.count', 3)
            ->has('blocks', 0));
});

test('block library picker filters blocks by category', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();

    $this->get(route('pages.blocks.create', [$page, 'category' => 'hero']))
        ->assertOk()
        ->assertInertia(fn ($response) => $response
            ->component('blocks/create')
            ->where('category', 'hero')
            ->where('activeCategory.name', 'Hero Sections')
            ->has('blocks', 3)
            ->where('blocks.0.type', 'hero_centered')
            ->where('blocks.0.name', 'Hero Centered'));

    $this->get(route('pages.blocks.create', [$page, 'category' => 'content']))
        ->assertOk()
        ->assertInertia(fn ($response) => $response
            ->has('blocks', 2)
            ->where('blocks.0.type', 'content_simple'));

    $this->get(route('pages.blocks.create', [$page, 'category' => 'feature']))
        ->assertOk()
        ->assertInertia(fn ($response) => $response
            ->has('blocks', 0));
});

test('invalid block category shows the section index', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();

    $this->get(route('pages.blocks.create', [$page, 'category' => 'not-real']))
        ->assertOk()
        ->assertInertia(fn ($response) => $response
            ->where('category', null)
            ->has('blocks', 0));
});
