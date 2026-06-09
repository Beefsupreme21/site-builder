<?php

use App\Models\Site;
use App\Support\ColorPalette;
use Database\Seeders\SiteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('preview renders the page', function () {
    $site = Site::factory()->create([
        'slug' => 'demo-dental',
        'company_name' => 'Demo Dental',
    ]);

    $home = $site->homePage();

    $this->get(route('sites.preview', [$site, $home]))
        ->assertOk()
        ->assertViewIs('preview.show')
        ->assertSee('Demo Dental', false);
});

test('preview home redirects to first page', function () {
    $site = Site::factory()->create(['slug' => 'demo-home']);

    $this->get(route('sites.preview.home', $site))
        ->assertRedirect(route('sites.preview', [$site, $site->homePage()]));
});

test('preview returns 404 for unknown slug', function () {
    $this->get('/preview/unknown-slug-xyz')->assertNotFound();
});

test('preview returns 404 for unknown page slug', function () {
    $site = Site::factory()->create();

    $this->get("/preview/{$site->slug}/does-not-exist")->assertNotFound();
});

test('new sites receive a home page named after the company', function () {
    $site = Site::factory()->create(['company_name' => 'Acme Co']);

    expect($site->pages)->toHaveCount(1);
    expect($site->homePage()?->slug)->toBe('home');
    expect($site->homePage()?->title)->toBe('Acme Co');
});

test('site show lists pages', function () {
    $site = Site::factory()->create(['company_name' => 'Acme']);

    $this->get(route('sites.show', $site))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('sites/show')
            ->has('site.pages', 1)
            ->missing('site.pages.0.block_pages'));
});

test('page show lists blocks for a page', function () {
    $site = Site::factory()->create(['company_name' => 'Acme']);
    $page = $site->homePage();
    $page->blockPages()->create([
        'content' => '<p>Hello</p>',
        'sort_order' => 1,
    ]);

    $this->get(route('sites.pages.show', [$site, $page]))
        ->assertOk()
        ->assertInertia(fn ($response) => $response
            ->component('site-pages/show')
            ->has('page.block_pages', 1));
});

test('pages can be added and removed', function () {
    $site = Site::factory()->create();

    $this->post(route('sites.pages.store', $site), [
        'slug' => 'store',
        'title' => 'Store',
        'sort_order' => 3,
    ])->assertRedirect(route('sites.show', $site));

    $storePage = $site->pages()->where('slug', 'store')->first();
    expect($storePage)->not->toBeNull();

    $this->get(route('sites.preview', [$site, $storePage]))
        ->assertOk()
        ->assertSee('Store', false);

    $this->delete(route('sites.pages.destroy', [$site, $storePage]))
        ->assertRedirect(route('sites.show', $site));

    expect($site->pages()->where('slug', 'store')->exists())->toBeFalse();
});

test('page update persists changes', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();

    $this->patch(route('sites.pages.update', [$site, $page]), [
        'slug' => 'home',
        'title' => 'Welcome',
        'sort_order' => 0,
    ])->assertRedirect(route('sites.pages.show', [$site, $page]));

    expect($page->fresh()->title)->toBe('Welcome');
});

test('site stores primary and secondary colors', function () {
    $this->post(route('sites.store'), [
        'slug' => 'branded-co',
        'company_name' => 'Branded Co',
        'primary_color' => '#2563eb',
        'secondary_color' => '#abc',
    ])->assertRedirect();

    $site = Site::query()->where('slug', 'branded-co')->first();

    expect($site)->not->toBeNull();
    expect($site->primary_color)->toBe('#2563EB');
    expect($site->secondary_color)->toBe('#AABBCC');
});

test('seeded sites include brand colors', function () {
    $this->seed(SiteSeeder::class);

    $site = Site::query()->where('slug', 'blue-ocean-dental')->first();

    expect($site->primary_color)->toBe('#0284C7');
    expect($site->secondary_color)->toBe('#0F766E');
});

test('brand styles fall back to defaults when no site is provided', function () {
    $html = view('preview.brand-styles')->render();

    expect($html)->toContain('--primary: '.ColorPalette::fromHex(ColorPalette::DEFAULT_PRIMARY)[500]);
    expect($html)->toContain('--secondary: '.ColorPalette::fromHex(ColorPalette::DEFAULT_SECONDARY)[500]);
});

test('preview injects color theme variables when site has brand colors', function () {
    $site = Site::factory()->create([
        'primary_color' => '#2563EB',
        'secondary_color' => '#64748B',
    ]);

    $this->get(route('sites.preview', [$site, $site->homePage()]))
        ->assertOk()
        ->assertSee('--primary: #2563EB', false)
        ->assertSee('--secondary:', false)
        ->assertSee('#2563EB', false);
});
