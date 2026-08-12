<?php

use App\Models\Site;
use App\Models\Template;
use Database\Seeders\TemplateSeeder;

beforeEach(function (): void {
    $this->seed(TemplateSeeder::class);
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
    $template = Template::query()->where('type', 'hero_centered')->firstOrFail();

    $page->blocks()->create([
        'content' => '<p data-test-block>Hello from the hero block</p>',
        'order' => 1,
    ]);

    $this->get(route('preview.show', $page))
        ->assertOk()
        ->assertSee('Hello from the hero block', false)
        ->assertSee('data-test-block', false)
        ->assertSee('@tailwindcss/browser@4', false);

    $page->blocks()->create([
        'content' => $template->default_content,
        'order' => 2,
    ]);

    $this->get(route('preview.show', $page))
        ->assertOk()
        ->assertSee('Welcome to your site', false);
});

test('preview does not render built-in site navigation or footer', function () {
    $site = Site::factory()->create([
        'slug' => 'nav-free',
        'company_name' => 'Nav Free Co',
    ]);

    $page = $site->homePage();
    $page->update(['title' => 'Home Page']);

    $this->get(route('preview.show', $page))
        ->assertOk()
        ->assertDontSee('aria-label="Site"', false)
        ->assertDontSee('&copy; '.now()->year, false);
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
