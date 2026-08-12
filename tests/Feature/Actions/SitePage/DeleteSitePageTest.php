<?php

use App\Actions\SitePage\DeleteSitePage;
use App\Models\Block;
use App\Models\Site;
use App\Models\SitePage;

test('deletes the page', function () {
    $site = Site::factory()->create();
    $page = $site->pages()->create([
        'slug' => 'about',
        'title' => 'About',
        'order' => 1,
        'layout_id' => $site->defaultLayout()->id,
    ]);

    (new DeleteSitePage)->handle($page);

    expect(SitePage::whereKey($page->id)->exists())->toBeFalse();
});

test('deletes the blocks belonging to the page', function () {
    $site = Site::factory()->create();
    $page = $site->pages()->create([
        'slug' => 'about',
        'title' => 'About',
        'order' => 1,
        'layout_id' => $site->defaultLayout()->id,
    ]);
    $page->blocks()->create(['content' => '<p>Hi</p>', 'order' => 1]);
    $pageId = $page->id;

    (new DeleteSitePage)->handle($page);

    expect(Block::query()
        ->where('blockable_type', SitePage::class)
        ->where('blockable_id', $pageId)
        ->count())->toBe(0);
});

test('leaves the other pages on the site alone', function () {
    $site = Site::factory()->create();
    $page = $site->pages()->create([
        'slug' => 'about',
        'title' => 'About',
        'order' => 1,
        'layout_id' => $site->defaultLayout()->id,
    ]);

    (new DeleteSitePage)->handle($page);

    expect($site->fresh()->pages)->toHaveCount(1);
});
