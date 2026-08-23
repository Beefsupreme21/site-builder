<?php

use App\Models\Site;
use App\Support\PreviewContent;
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

    $site = Site::query()->where('slug', 'acme')->firstOrFail();
    $about = $site->pages()->where('slug', 'about')->firstOrFail();
    $contact = $site->pages()->where('slug', 'contact')->firstOrFail();

    $this->get('/preview/acme/home')
        ->assertOk()
        ->assertSee(route('preview.show', [$site, $about]), false)
        ->assertSee(route('preview.show', [$site, $contact]), false);
});

test('stored block html keeps site-root links not preview paths', function () {
    $this->seed(TemplateSeeder::class);
    $this->seed(SiteSeeder::class);

    $site = Site::query()->where('slug', 'acme')->firstOrFail();
    $nav = $site->defaultLayout()?->blocks()->whereHas('template', fn ($q) => $q->where('type', 'nav_top'))->first();

    expect($nav?->content)->toContain('href="/about"');
    expect($nav?->content)->not->toContain('/preview/');
});
