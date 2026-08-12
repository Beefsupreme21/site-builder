<?php

use App\Actions\Site\DeleteSite;
use App\Models\Block;
use App\Models\Layout;
use App\Models\Site;
use App\Models\SitePage;

test('deletes the site', function () {
    $site = Site::factory()->create();

    (new DeleteSite)->handle($site);

    expect(Site::count())->toBe(0);
});

test('deletes the pages belonging to the site', function () {
    $site = Site::factory()->create();

    (new DeleteSite)->handle($site);

    expect(SitePage::count())->toBe(0);
});

test('deletes the layouts belonging to the site', function () {
    $site = Site::factory()->create();

    (new DeleteSite)->handle($site);

    expect(Layout::count())->toBe(0);
});

test('deletes the blocks belonging to the site', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();
    $page->blocks()->create(['content' => '<p>Hi</p>', 'order' => 1]);

    (new DeleteSite)->handle($site);

    expect(Block::count())->toBe(0);
});
