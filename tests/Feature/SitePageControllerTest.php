<?php

use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

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

    $this->get(route('preview.show', $storePage))
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
