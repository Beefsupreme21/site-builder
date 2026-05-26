<?php

use App\Models\Site;
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
        ->assertViewIs('sites.show')
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
            ->has('site.pages', 1));
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
    ])->assertRedirect(route('sites.show', $site));

    expect($page->fresh()->title)->toBe('Welcome');
});
