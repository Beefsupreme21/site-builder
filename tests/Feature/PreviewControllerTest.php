<?php

use App\Models\Block;
use App\Models\Site;
use Database\Seeders\BlockSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(BlockSeeder::class);
});

test('preview renders the page', function () {
    $site = Site::factory()->create([
        'slug' => 'demo-dental',
        'company_name' => 'Demo Dental',
    ]);

    $home = $site->homePage();

    $this->get(route('preview.show', $home))
        ->assertOk()
        ->assertViewIs('preview.show')
        ->assertSee('Demo Dental', false);
});

test('preview index redirects to the home page', function () {
    $site = Site::factory()->create(['slug' => 'demo-home']);

    $this->get(route('preview.index', $site))
        ->assertRedirect(route('preview.show', $site->homePage()));
});

test('preview returns 404 for unknown site slug', function () {
    $this->get('/preview/sites/unknown-slug-xyz')->assertNotFound();
});

test('preview returns 404 for unknown page id', function () {
    $this->get('/preview/999999')->assertNotFound();
});

test('preview renders the block content for a page', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = Block::query()->where('type', 'hero_centered')->firstOrFail();

    $page->blockPages()->create([
        'content' => '<p data-test-block>Hello from the hero block</p>',
        'sort_order' => 1,
    ]);

    $this->get(route('preview.show', $page))
        ->assertOk()
        ->assertSee('Hello from the hero block', false)
        ->assertSee('data-test-block', false)
        ->assertSee('@tailwindcss/browser@4', false);

    $page->blockPages()->create([
        'content' => $block->default_content,
        'sort_order' => 2,
    ]);

    $this->get(route('preview.show', $page))
        ->assertOk()
        ->assertSee('Welcome to your site', false);
});

test('preview does not inject brand color styles', function () {
    $site = Site::factory()->create([
        'primary_color' => '#2563EB',
        'secondary_color' => '#64748B',
    ]);

    $this->get(route('preview.show', $site->homePage()))
        ->assertOk()
        ->assertDontSee('--primary:', false)
        ->assertDontSee('brand-styles', false);
});
