<?php

use App\Actions\Site\DeleteSite;
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
