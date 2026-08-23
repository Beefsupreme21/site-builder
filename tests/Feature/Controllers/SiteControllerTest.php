<?php

use App\Models\Site;
use Database\Seeders\SiteSeeder;
use Database\Seeders\TemplateSeeder;

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
            ->missing('site.pages.0.blocks'));
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

test('validation failures from the action flash errors back to the form', function () {
    $this->from(route('sites.create'))
        ->post(route('sites.store'), ['slug' => '', 'company_name' => ''])
        ->assertRedirect(route('sites.create'))
        ->assertSessionHasErrors(['slug', 'company_name', 'primary_color', 'secondary_color']);

    expect(Site::count())->toBe(0);
});

test('seeded sites include brand colors', function () {
    $this->seed(SiteSeeder::class);

    $site = Site::query()->where('slug', 'acme')->first();

    expect($site->primary_color)->toBe('#4F46E5');
    expect($site->secondary_color)->toBe('#1E293B');
});

test('seeded multipage starter includes curated pages and layout blocks', function () {
    $this->seed(TemplateSeeder::class);
    $this->seed(SiteSeeder::class);

    $site = Site::query()->where('slug', 'acme')->first();

    expect($site->pages()->orderBy('order')->pluck('slug')->all())
        ->toBe(['home', 'about', 'contact']);

    expect($site->defaultLayout()?->blocks()->with('template')->orderBy('order')->get()->pluck('template.type')->all())
        ->toBe(['nav_top', 'slot', 'footer_social']);

    expect($site->homePage()?->blocks()->count())->toBe(2);
});

test('seeded landing starter is a single page with slot-only layout', function () {
    $this->seed(TemplateSeeder::class);
    $this->seed(SiteSeeder::class);

    $site = Site::query()->where('slug', 'northwind')->first();

    expect($site->pages()->count())->toBe(1);
    expect($site->defaultLayout()?->blocks()->with('template')->orderBy('order')->get()->pluck('template.type')->all())
        ->toBe(['slot']);
    expect($site->homePage()?->blocks()->count())->toBe(3);
});

test('home redirects to the sites index', function () {
    $this->get('/')->assertRedirect(route('sites.index'));
});

test('sites index lists sites ordered by company name', function () {
    Site::factory()->create(['company_name' => 'Zulu Co', 'slug' => 'zulu']);
    Site::factory()->create(['company_name' => 'Alpha Co', 'slug' => 'alpha']);

    $this->get(route('sites.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('sites/index')
            ->where('sites.0.company_name', 'Alpha Co')
            ->where('sites.1.company_name', 'Zulu Co'));
});

test('site show includes the default layout', function () {
    $site = Site::factory()->create();

    $this->get(route('sites.show', $site))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('defaultLayout')
            ->where('defaultLayout.name', 'Default'));
});

test('site update persists changes and redirects to show', function () {
    $site = Site::factory()->create(['slug' => 'before', 'company_name' => 'Before Co']);

    $this->patch(route('sites.update', $site), [
        'slug' => 'after',
        'company_name' => 'After Co',
        'phone' => '(555) 111-2222',
        'email' => 'after@example.com',
        'logo' => null,
        'primary_color' => '#111111',
        'secondary_color' => '#222222',
    ])->assertRedirect(route('sites.show', $site));

    expect($site->fresh()->slug)->toBe('after');
    expect($site->fresh()->company_name)->toBe('After Co');
});

test('site update validation failures flash errors back to the edit form', function () {
    $site = Site::factory()->create();

    $this->from(route('sites.edit', $site))
        ->patch(route('sites.update', $site), [
            'slug' => '',
            'company_name' => '',
            'primary_color' => 'red',
            'secondary_color' => 'blue',
        ])
        ->assertRedirect(route('sites.edit', $site))
        ->assertSessionHasErrors(['slug', 'company_name', 'primary_color', 'secondary_color']);
});

test('site destroy deletes the site and redirects to the index', function () {
    $site = Site::factory()->create();

    $this->delete(route('sites.destroy', $site))
        ->assertRedirect(route('sites.index'));

    expect(Site::count())->toBe(0);
});
