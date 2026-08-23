<?php

use App\Models\Site;
use App\Models\Template;

test('page show lists blocks for a page', function () {
    $site = Site::factory()->create(['company_name' => 'Acme']);
    $page = $site->homePage();
    $template = Template::query()->where('type', 'hero_centered')->firstOrFail();

    $page->blocks()->create([
        'template_id' => $template->id,
        'content' => '<p>Hello</p>',
        'order' => 1,
    ]);

    $this->get(route('sites.pages.show', [$site, $page]))
        ->assertOk()
        ->assertInertia(fn ($response) => $response
            ->component('site-pages/show')
            ->has('page.blocks', 1)
            ->where('page.blocks.0.template.name', 'Hero Centered'));
});

test('pages can be added and removed', function () {
    $site = Site::factory()->create();

    $this->post(route('sites.pages.store', $site), [
        'slug' => 'store',
        'title' => 'Store',
        'order' => 3,
    ])->assertRedirect(route('sites.show', $site));

    $storePage = $site->pages()->where('slug', 'store')->first();
    expect($storePage)->not->toBeNull();

    $this->get(route('preview.show', [$site, $storePage]))
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
        'order' => 0,
    ])->assertRedirect(route('sites.pages.show', [$site, $page]));

    expect($page->fresh()->title)->toBe('Welcome');
});

test('page store validation failures flash errors back to the create form', function () {
    $site = Site::factory()->create();

    $this->from(route('sites.pages.create', $site))
        ->post(route('sites.pages.store', $site), [
            'slug' => 'Bad Slug',
            'title' => '',
        ])
        ->assertRedirect(route('sites.pages.create', $site))
        ->assertSessionHasErrors(['slug', 'title']);
});

test('page update validation failures flash errors back to the edit form', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();

    $this->from(route('sites.pages.edit', [$site, $page]))
        ->patch(route('sites.pages.update', [$site, $page]), [
            'slug' => 'Bad Slug',
            'title' => '',
        ])
        ->assertRedirect(route('sites.pages.edit', [$site, $page]))
        ->assertSessionHasErrors(['slug', 'title']);
});

test('a page accessed under the wrong site returns 404', function () {
    $site = Site::factory()->create();
    $other = Site::factory()->create();
    $page = $site->pages()->create([
        'slug' => 'about',
        'title' => 'About',
        'order' => 1,
        'layout_id' => $site->defaultLayout()->id,
    ]);

    $this->get(route('sites.pages.show', [$other, $page]))->assertNotFound();
});
