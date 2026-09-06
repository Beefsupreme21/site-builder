<?php

use App\Models\Site;
use App\Models\Template;
use App\Support\PreviewContent;
use App\Support\SiteLogo;
use Database\Seeders\SiteSeeder;
use Database\Seeders\TemplateSeeder;

test('preview rewrites site-root links to preview urls', function () {
    $site = Site::factory()->create(['slug' => 'acme-co']);
    $home = $site->homePage();
    $about = $site->pages()->create([
        'slug' => 'about',
        'title' => 'About',
        'order' => 1,
        'layout_id' => $site->defaultLayout()->id,
    ]);

    $html = PreviewContent::for($site)->render(
        '<a href="/">Home</a><a href="/about">About</a><a href="/missing">Missing</a><a href="https://example.com">External</a>',
    );

    expect($html)
        ->toContain(route('preview.show', [$site, $home]))
        ->toContain(route('preview.show', [$site, $about]))
        ->toContain('href="/missing"')
        ->toContain('href="https://example.com"');
});

test('seeded multipage preview nav links resolve in preview html', function () {
    $this->seed(TemplateSeeder::class);
    $this->seed(SiteSeeder::class);

    $site = Site::query()->where('slug', 'fernwood')->firstOrFail();
    $about = $site->pages()->where('slug', 'about')->firstOrFail();
    $contact = $site->pages()->where('slug', 'contact')->firstOrFail();

    $this->get('/preview/fernwood/home')
        ->assertOk()
        ->assertSee(route('preview.show', [$site, $about]), false)
        ->assertSee(route('preview.show', [$site, $contact]), false);
});

test('stored block html keeps site-root links not preview paths', function () {
    $this->seed(TemplateSeeder::class);

    $site = Site::factory()->create();
    $layout = $site->defaultLayout();
    $nav = Template::query()->where('type', 'nav_top')->firstOrFail();

    $block = $layout->blocks()->create([
        'template_id' => $nav->id,
        'content' => $nav->default_content,
        'order' => 0,
    ]);

    expect($block->content)->toContain('href="/about"');
    expect($block->content)->not->toContain('/preview/');
});

test('preview layout headers render the site logo from settings', function () {
    $this->seed(TemplateSeeder::class);
    $this->seed(SiteSeeder::class);

    $site = Site::query()->where('slug', 'ridgeline')->firstOrFail();
    $site->update(['logo' => '/images/logo.png']);

    $this->get('/preview/ridgeline/home')
        ->assertOk()
        ->assertSee(SiteLogo::url('/images/logo.png'), false)
        ->assertDontSee('>RC<', false);
});
