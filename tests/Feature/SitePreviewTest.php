<?php

use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('preview renders default page', function () {
    $site = Site::factory()->create([
        'slug' => 'demo-dental',
        'template' => 'default',
        'company_name' => 'Demo Dental',
    ]);

    $home = $site->homePage();

    $this->get(route('sites.preview', [$site, $home]))
        ->assertOk()
        ->assertViewIs('sites.templates.default.pages.show')
        ->assertSee('Demo Dental', false);
});

test('preview home redirects to first page', function () {
    $site = Site::factory()->create(['slug' => 'demo-home']);

    $this->get(route('sites.preview.home', $site))
        ->assertRedirect(route('sites.preview', [$site, $site->homePage()]));
});

test('preview renders alternate page by slug', function () {
    $site = Site::factory()->create([
        'slug' => 'demo-gym',
        'template' => 'alternate',
    ]);

    $about = $site->pages()->where('slug', 'about')->first();

    $this->get(route('sites.preview', [$site, $about]))
        ->assertOk()
        ->assertViewIs('sites.templates.alternate.pages.show')
        ->assertSee('About Us', false);
});

test('preview falls back to default template for unknown template value', function () {
    $site = Site::factory()->create([
        'slug' => 'legacy-site',
        'template' => 'legacy-removed',
    ]);

    $this->get(route('sites.preview.home', $site))
        ->assertRedirect();

    $home = $site->homePage();

    $this->get(route('sites.preview', [$site, $home]))
        ->assertOk()
        ->assertViewIs('sites.templates.default.pages.show');
});

test('preview returns 404 for unknown slug', function () {
    $this->get('/preview/unknown-slug-xyz')->assertNotFound();
});

test('preview returns 404 for unknown page slug', function () {
    $site = Site::factory()->create();

    $this->get("/preview/{$site->slug}/does-not-exist")->assertNotFound();
});

test('new sites receive default pages', function () {
    $site = Site::factory()->create();

    expect($site->pages)->toHaveCount(3);
    expect($site->homePage()?->title)->toBe($site->company_name);
});

test('site show lists pages', function () {
    $site = Site::factory()->create(['company_name' => 'Acme']);

    $this->get(route('sites.show', $site))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('sites/show')
            ->has('site.pages', 3));
});

test('pages can be added and removed', function () {
    $site = Site::factory()->create();

    $this->post(route('sites.pages.store', $site), [
        'slug' => 'store',
        'title' => 'Store',
        'content' => 'Browse our products.',
        'sort_order' => 3,
    ])->assertRedirect(route('sites.show', $site));

    $storePage = $site->pages()->where('slug', 'store')->first();
    expect($storePage)->not->toBeNull();

    $this->get(route('sites.preview', [$site, $storePage]))
        ->assertOk()
        ->assertSee('Browse our products.', false);

    $about = $site->pages()->where('slug', 'about')->first();

    $this->delete(route('sites.pages.destroy', [$site, $about]))
        ->assertRedirect(route('sites.show', $site));

    expect($site->pages()->where('slug', 'about')->exists())->toBeFalse();
});

test('page update persists changes', function () {
    $site = Site::factory()->create();
    $page = $site->pages()->where('slug', 'about')->first();

    $this->patch(route('sites.pages.update', [$site, $page]), [
        'slug' => 'about',
        'title' => 'Our story',
        'content' => 'Updated about copy.',
        'sort_order' => 1,
    ])->assertRedirect(route('sites.show', $site));

    expect($page->fresh()->title)->toBe('Our story');
    expect($page->fresh()->content)->toBe('Updated about copy.');
});
