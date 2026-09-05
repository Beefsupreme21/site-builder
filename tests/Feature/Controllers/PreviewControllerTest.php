<?php

use App\Models\Site;
use App\Models\Template;
use Database\Seeders\SiteSeeder;
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

    $this->get(route('preview.show', [$site, $home]))
        ->assertOk()
        ->assertViewIs('preview.show')
        ->assertSee('Demo Dental', false);
});

test('preview index redirects to the home page', function () {
    $site = Site::factory()->create(['slug' => 'demo-home']);

    $this->get(route('preview.index', $site))
        ->assertRedirect(route('preview.show', [$site, $site->homePage()]));
});

test('preview returns 404 for unknown site slug', function () {
    $this->get('/preview/unknown-slug-xyz')->assertNotFound();
});

test('preview returns 404 for unknown page slug', function () {
    $site = Site::factory()->create(['slug' => 'demo-site']);

    $this->get('/preview/demo-site/missing-page')->assertNotFound();
});

test('preview returns 404 when page belongs to another site', function () {
    Site::factory()->create(['slug' => 'demo-site']);
    $other = Site::factory()->create(['slug' => 'other-site']);
    $other->pages()->create([
        'slug' => 'team',
        'title' => 'Team',
        'order' => 1,
        'layout_id' => $other->defaultLayout()->id,
    ]);

    $this->get('/preview/demo-site/team')->assertNotFound();
});

test('preview renders the block content for a page', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();
    $template = Template::query()->where('type', 'hero_centered')->firstOrFail();

    $page->blocks()->create([
        'content' => '<p data-test-block>Hello from the hero block</p>',
        'order' => 1,
    ]);

    $this->get(route('preview.show', [$site, $page]))
        ->assertOk()
        ->assertSee('Hello from the hero block', false)
        ->assertSee('data-test-block', false)
        ->assertSee('@tailwindcss/browser@4', false);

    $page->blocks()->create([
        'content' => $template->default_content,
        'order' => 2,
    ]);

    $this->get(route('preview.show', [$site, $page]))
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

    $this->get(route('preview.show', [$site, $page]))
        ->assertOk()
        ->assertDontSee('aria-label="Site"', false)
        ->assertDontSee('&copy; '.now()->year, false);
});

test('preview exposes a nine step ramp for each brand color', function () {
    $site = Site::factory()->create([
        'primary_color' => '#2563EB',
        'secondary_color' => '#64748B',
    ]);

    $response = $this->get(route('preview.show', [$site, $site->homePage()]))->assertOk();

    $response->assertSee('--primary-500: #2563EB;', false)
        ->assertSee('--secondary-500: #64748B;', false);

    foreach ([100, 200, 300, 400, 600, 700, 800, 900] as $step) {
        $response->assertSee("--primary-{$step}: color-mix(", false)
            ->assertSee("--secondary-{$step}: color-mix(", false);
    }
});

test('preview renders any page by site and page slug', function () {
    $this->seed(SiteSeeder::class);

    $site = Site::query()->where('slug', 'acme')->firstOrFail();
    $about = $site->pages()->where('slug', 'about')->firstOrFail();

    $this->get('/preview/acme/about')
        ->assertOk()
        ->assertSee('A simple centered hero', false);

    $this->get('/preview/acme/contact')
        ->assertOk()
        ->assertSee('Get in touch', false);
});

test('preview index returns 404 when the site has no home page', function () {
    $site = Site::factory()->create();
    $site->pages()->delete();

    $this->get(route('preview.index', $site))->assertNotFound();
});
