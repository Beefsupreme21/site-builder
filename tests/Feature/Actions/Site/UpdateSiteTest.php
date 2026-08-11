<?php

use App\Actions\Site\UpdateSite;
use App\Models\Site;
use Illuminate\Validation\ValidationException;

function updateSiteInput(array $overrides = []): array
{
    return array_merge([
        'slug' => 'renamed-site',
        'company_name' => 'Renamed Co.',
        'phone' => '(555) 987-6543',
        'email' => 'hi@renamed.example',
        'logo' => null,
        'primary_color' => '#0284C7',
        'secondary_color' => '#0F766E',
    ], $overrides);
}

test('updates the site', function () {
    $site = Site::factory()->create(['slug' => 'original', 'company_name' => 'Original Co.']);

    (new UpdateSite)->handle($site, updateSiteInput());

    expect($site->fresh()->slug)->toBe('renamed-site');
    expect($site->fresh()->company_name)->toBe('Renamed Co.');
});

test('allows a site to keep its own slug', function () {
    $site = Site::factory()->create(['slug' => 'keep-me']);

    (new UpdateSite)->handle($site, updateSiteInput(['slug' => 'keep-me']));

    expect($site->fresh()->slug)->toBe('keep-me');
});

test('normalizes brand colors on update', function () {
    $site = Site::factory()->create();

    (new UpdateSite)->handle($site, updateSiteInput(['primary_color' => '#abc']));

    expect($site->fresh()->primary_color)->toBe('#AABBCC');
});

test('rejects a slug taken by another site', function () {
    Site::factory()->create(['slug' => 'taken']);
    $site = Site::factory()->create(['slug' => 'mine']);

    (new UpdateSite)->handle($site, updateSiteInput(['slug' => 'taken']));
})->throws(ValidationException::class);

test('requires a company name', function () {
    $site = Site::factory()->create();

    (new UpdateSite)->handle($site, updateSiteInput(['company_name' => '']));
})->throws(ValidationException::class);

test('rejects a malformed brand color', function () {
    $site = Site::factory()->create();

    (new UpdateSite)->handle($site, updateSiteInput(['secondary_color' => 'blue']));
})->throws(ValidationException::class);
