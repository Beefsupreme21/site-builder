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

    $site = Site::query()->where('slug', 'ridgeline')->first();

    expect($site->primary_color)->toBe('#92400E');
    expect($site->secondary_color)->toBe('#1C1917');
});

test('seeded starters use matching company names', function () {
    $this->seed(TemplateSeeder::class);
    $this->seed(SiteSeeder::class);

    expect(Site::query()->where('slug', 'ridgeline')->value('company_name'))->toBe('Ridgeline Coffee');
    expect(Site::query()->where('slug', 'fernwood')->value('company_name'))->toBe('Fernwood Dental');
    expect(Site::query()->where('slug', 'willow')->value('company_name'))->toBe('Willow');
});

test('preview starter blocks use the site primary color css variables', function () {
    $this->seed(TemplateSeeder::class);
    $this->seed(SiteSeeder::class);

    $site = Site::query()->where('slug', 'willow')->firstOrFail();
    $site->update(['primary_color' => '#7C3AED']);

    $this->get('/preview/willow/home')
        ->assertOk()
        ->assertSee('--primary-500: #7C3AED;', false)
        ->assertSee('bg-[var(--primary-700)]', false)
        ->assertDontSee('bg-emerald-800', false);
});

test('seeded starters are ridgeline fernwood and willow only', function () {
    $this->seed(TemplateSeeder::class);
    $this->seed(SiteSeeder::class);

    expect(Site::query()->orderBy('slug')->pluck('slug')->all())
        ->toBe(['fernwood', 'ridgeline', 'willow']);
});

test('seeded single page starter has chrome and five stacked sections', function () {
    $this->seed(TemplateSeeder::class);
    $this->seed(SiteSeeder::class);

    $site = Site::query()->where('slug', 'ridgeline')->firstOrFail();

    expect($site->pages()->count())->toBe(1);
    expect($site->homePage()?->slug)->toBe('home');

    expect($site->defaultLayout()?->blocks()->with('template')->orderBy('order')->get()->pluck('template.type')->all())
        ->toBe(['header_anchor', 'slot', 'footer_local']);

    expect($site->homePage()?->blocks()->with('template')->orderBy('order')->get()->pluck('template.type')->all())
        ->toBe(['hero_local', 'services_cards', 'story_split', 'hours_location', 'cta_banner']);
});

test('single page starter renders its sections in the preview', function () {
    $this->seed(TemplateSeeder::class);
    $this->seed(SiteSeeder::class);

    $this->get('/preview/ridgeline/home')
        ->assertOk()
        ->assertSee('Coffee worth walking up the hill for', false)
        ->assertSee('What we pour', false)
        ->assertSee('A roastery first', false)
        ->assertSee('Opening hours', false)
        ->assertSee('Beans on your doorstep every other Friday', false);
});

test('seeded practice starter has three pages sharing one layout', function () {
    $this->seed(TemplateSeeder::class);
    $this->seed(SiteSeeder::class);

    $site = Site::query()->where('slug', 'fernwood')->firstOrFail();

    expect($site->pages()->orderBy('order')->pluck('slug')->all())
        ->toBe(['home', 'about', 'contact']);

    expect($site->defaultLayout()?->blocks()->with('template')->orderBy('order')->get()->pluck('template.type')->all())
        ->toBe(['header_practice', 'slot', 'footer_columns']);

    expect($site->pages()->pluck('layout_id')->unique())->toHaveCount(1);
});

test('seeded services starter has three pages and reuses its cta across two', function () {
    $this->seed(TemplateSeeder::class);
    $this->seed(SiteSeeder::class);

    $site = Site::query()->where('slug', 'willow')->firstOrFail();

    expect($site->pages()->orderBy('order')->pluck('slug')->all())
        ->toBe(['home', 'services', 'contact']);

    expect($site->defaultLayout()?->blocks()->with('template')->orderBy('order')->get()->pluck('template.type')->all())
        ->toBe(['header_studio', 'slot', 'footer_studio']);

    expect($site->pages()->where('slug', 'services')->first()?->blocks()->with('template')->orderBy('order')->get()->pluck('template.type')->all())
        ->toBe(['services_detail', 'cta_quote']);
});

test('multipage starters rewrite their nav links to preview urls', function (string $slug, string $navLink) {
    $this->seed(TemplateSeeder::class);
    $this->seed(SiteSeeder::class);

    $site = Site::query()->where('slug', $slug)->firstOrFail();
    $contact = $site->pages()->where('slug', 'contact')->firstOrFail();

    $this->get("/preview/{$slug}/home")
        ->assertOk()
        ->assertSee(route('preview.show', [$site, $contact]), false)
        ->assertSee($navLink, false)
        ->assertDontSee('href="/contact"', false);
})->with([
    'practice' => ['fernwood', 'Book a visit'],
    'services' => ['willow', 'Request a consultation'],
]);

test('home redirects to the sites index', function () {
    $this->get('/')->assertRedirect(route('sites.index'));
});

test('inertia shares a cache busted app logo url', function () {
    $logoPath = public_path('images/logo.png');

    $this->get(route('sites.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('logo', asset('images/logo.png').'?v='.filemtime($logoPath))
            ->where('name', config('app.name')));
});

test('sites index lists sites ordered by company name', function () {
    Site::factory()->create(['company_name' => 'Alpha Co', 'slug' => 'alpha']);
    Site::factory()->create(['company_name' => 'Zulu Co', 'slug' => 'zulu']);

    $this->get(route('sites.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('sites/index')
            ->where('sites.0.company_name', 'Alpha Co')
            ->where('sites.0.slug', 'alpha')
            ->where('sites.1.company_name', 'Zulu Co')
            ->where('sites.1.slug', 'zulu'));
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
