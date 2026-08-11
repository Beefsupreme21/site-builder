<?php

use App\Actions\SitePage\DeleteSitePage;
use App\Models\BlockPage;
use App\Models\Site;
use App\Models\SitePage;

test('deletes the page', function () {
    $site = Site::factory()->create();
    $page = $site->pages()->create(['slug' => 'about', 'title' => 'About', 'sort_order' => 1]);

    (new DeleteSitePage)->handle($page);

    expect(SitePage::whereKey($page->id)->exists())->toBeFalse();
});

test('deletes the blocks belonging to the page', function () {
    $site = Site::factory()->create();
    $page = $site->pages()->create(['slug' => 'about', 'title' => 'About', 'sort_order' => 1]);
    $page->blockPages()->create(['content' => '<p>Hi</p>', 'sort_order' => 1]);

    (new DeleteSitePage)->handle($page);

    expect(BlockPage::count())->toBe(0);
});

test('leaves the other pages on the site alone', function () {
    $site = Site::factory()->create();
    $page = $site->pages()->create(['slug' => 'about', 'title' => 'About', 'sort_order' => 1]);

    (new DeleteSitePage)->handle($page);

    expect($site->fresh()->pages)->toHaveCount(1);
});
