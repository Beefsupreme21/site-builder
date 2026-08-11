<?php

use App\Actions\SitePage\CreateSitePage;
use App\Models\Site;
use App\Models\SitePage;
use Illuminate\Validation\ValidationException;

test('creates a page on the site', function () {
    $site = Site::factory()->create();

    $page = (new CreateSitePage)->handle($site, [
        'slug' => 'about',
        'title' => 'About Us',
    ]);

    expect($page->slug)->toBe('about');
    expect($page->title)->toBe('About Us');
    expect($page->site_id)->toBe($site->id);
});

test('defaults sort order to one past the current highest', function () {
    $site = Site::factory()->create();
    $site->pages()->create(['slug' => 'services', 'title' => 'Services', 'sort_order' => 4]);

    $page = (new CreateSitePage)->handle($site, [
        'slug' => 'about',
        'title' => 'About Us',
    ]);

    expect($page->sort_order)->toBe(5);
});

test('honors an explicit sort order', function () {
    $site = Site::factory()->create();

    $page = (new CreateSitePage)->handle($site, [
        'slug' => 'about',
        'title' => 'About Us',
        'sort_order' => 2,
    ]);

    expect($page->sort_order)->toBe(2);
});

test('allows the same slug on a different site', function () {
    $site = Site::factory()->create();
    $site->pages()->create(['slug' => 'about', 'title' => 'About', 'sort_order' => 1]);
    $other = Site::factory()->create();

    $page = (new CreateSitePage)->handle($other, [
        'slug' => 'about',
        'title' => 'About Them',
    ]);

    expect($page->site_id)->toBe($other->id);
    expect(SitePage::where('slug', 'about')->count())->toBe(2);
});

test('rejects a slug already used on the same site', function () {
    $site = Site::factory()->create();

    (new CreateSitePage)->handle($site, ['slug' => 'home', 'title' => 'Duplicate']);
})->throws(ValidationException::class);

test('requires a slug', function () {
    (new CreateSitePage)->handle(Site::factory()->create(), ['title' => 'About Us']);
})->throws(ValidationException::class);

test('rejects a slug that is not kebab case', function () {
    (new CreateSitePage)->handle(Site::factory()->create(), [
        'slug' => 'About Us',
        'title' => 'About Us',
    ]);
})->throws(ValidationException::class);

test('requires a title', function () {
    (new CreateSitePage)->handle(Site::factory()->create(), ['slug' => 'about']);
})->throws(ValidationException::class);

test('rejects a negative sort order', function () {
    (new CreateSitePage)->handle(Site::factory()->create(), [
        'slug' => 'about',
        'title' => 'About Us',
        'sort_order' => -1,
    ]);
})->throws(ValidationException::class);

test('does not create a page when validation fails', function () {
    $site = Site::factory()->create();

    try {
        (new CreateSitePage)->handle($site, ['slug' => 'about']);
    } catch (ValidationException) {
        // expected
    }

    expect(SitePage::where('slug', 'about')->count())->toBe(0);
});
