<?php

use App\Actions\SitePage\UpdateSitePage;
use App\Models\Site;
use Illuminate\Validation\ValidationException;

test('updates the page', function () {
    $site = Site::factory()->create();
    $page = $site->pages()->create(['slug' => 'about', 'title' => 'About', 'sort_order' => 1]);

    (new UpdateSitePage)->handle($page, ['slug' => 'about-us', 'title' => 'About Us']);

    expect($page->fresh()->slug)->toBe('about-us');
    expect($page->fresh()->title)->toBe('About Us');
});

test('allows a page to keep its own slug', function () {
    $site = Site::factory()->create();
    $page = $site->pages()->create(['slug' => 'about', 'title' => 'About', 'sort_order' => 1]);

    (new UpdateSitePage)->handle($page, ['slug' => 'about', 'title' => 'Renamed']);

    expect($page->fresh()->title)->toBe('Renamed');
});

test('allows a slug used by a page on another site', function () {
    $other = Site::factory()->create();
    $page = $other->pages()->create(['slug' => 'about', 'title' => 'About', 'sort_order' => 1]);

    (new UpdateSitePage)->handle($page, ['slug' => 'about-us', 'title' => 'About Us']);

    expect($page->fresh()->slug)->toBe('about-us');
});

test('rejects a slug taken by another page on the same site', function () {
    $site = Site::factory()->create();
    $page = $site->pages()->create(['slug' => 'about', 'title' => 'About', 'sort_order' => 1]);

    (new UpdateSitePage)->handle($page, ['slug' => 'home', 'title' => 'About']);
})->throws(ValidationException::class);

test('requires a title', function () {
    $site = Site::factory()->create();
    $page = $site->pages()->create(['slug' => 'about', 'title' => 'About', 'sort_order' => 1]);

    (new UpdateSitePage)->handle($page, ['slug' => 'about', 'title' => '']);
})->throws(ValidationException::class);

test('rejects a slug that is not kebab case', function () {
    $site = Site::factory()->create();
    $page = $site->pages()->create(['slug' => 'about', 'title' => 'About', 'sort_order' => 1]);

    (new UpdateSitePage)->handle($page, ['slug' => 'About Us', 'title' => 'About']);
})->throws(ValidationException::class);
