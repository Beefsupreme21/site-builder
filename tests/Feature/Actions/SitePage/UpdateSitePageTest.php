<?php

use App\Actions\SitePage\UpdateSitePage;
use App\Models\Site;
use App\Models\SitePage;
use Illuminate\Validation\ValidationException;

function pageForUpdate(Site $site, array $attributes = []): SitePage
{
    return $site->pages()->create(array_merge([
        'slug' => 'about',
        'title' => 'About',
        'order' => 1,
        'layout_id' => $site->defaultLayout()->id,
    ], $attributes));
}

test('updates the page', function () {
    $site = Site::factory()->create();
    $page = pageForUpdate($site);

    (new UpdateSitePage)->handle($page, ['slug' => 'about-us', 'title' => 'About Us']);

    expect($page->fresh()->slug)->toBe('about-us');
    expect($page->fresh()->title)->toBe('About Us');
});

test('allows a page to keep its own slug', function () {
    $site = Site::factory()->create();
    $page = pageForUpdate($site);

    (new UpdateSitePage)->handle($page, ['slug' => 'about', 'title' => 'Renamed']);

    expect($page->fresh()->title)->toBe('Renamed');
});

test('allows a slug used by a page on another site', function () {
    $other = Site::factory()->create();
    $page = pageForUpdate($other);

    (new UpdateSitePage)->handle($page, ['slug' => 'about-us', 'title' => 'About Us']);

    expect($page->fresh()->slug)->toBe('about-us');
});

test('rejects a slug taken by another page on the same site', function () {
    $site = Site::factory()->create();
    $page = pageForUpdate($site);

    (new UpdateSitePage)->handle($page, ['slug' => 'home', 'title' => 'About']);
})->throws(ValidationException::class);

test('requires a title', function () {
    $site = Site::factory()->create();
    $page = pageForUpdate($site);

    (new UpdateSitePage)->handle($page, ['slug' => 'about', 'title' => '']);
})->throws(ValidationException::class);

test('rejects a slug that is not kebab case', function () {
    $site = Site::factory()->create();
    $page = pageForUpdate($site);

    (new UpdateSitePage)->handle($page, ['slug' => 'About Us', 'title' => 'About']);
})->throws(ValidationException::class);

test('updates the page sort order', function () {
    $site = Site::factory()->create();
    $page = pageForUpdate($site);

    (new UpdateSitePage)->handle($page, ['slug' => 'about', 'title' => 'About', 'order' => 5]);

    expect($page->fresh()->order)->toBe(5);
});
