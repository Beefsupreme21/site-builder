<?php

use App\Actions\Site\CreateSite;
use App\Models\Site;
use Illuminate\Validation\ValidationException;

function createSiteInput(array $overrides = []): array
{
    return array_merge([
        'slug' => 'acme-hardware',
        'company_name' => 'Acme Hardware Co.',
        'phone' => '(555) 123-4567',
        'email' => 'hello@acme.example',
        'logo' => null,
        'primary_color' => '#B45309',
        'secondary_color' => '#44403C',
    ], $overrides);
}

test('creates a site and its default home page', function () {
    $site = (new CreateSite)->handle(createSiteInput());

    expect($site->slug)->toBe('acme-hardware');
    expect($site->company_name)->toBe('Acme Hardware Co.');
    expect($site->homePage()?->slug)->toBe('home');
    expect($site->homePage()?->title)->toBe('Acme Hardware Co.');
});

test('expands and upper-cases shorthand brand colors', function () {
    $site = (new CreateSite)->handle(createSiteInput([
        'primary_color' => '#abc',
        'secondary_color' => '#ff0000',
    ]));

    expect($site->primary_color)->toBe('#AABBCC');
    expect($site->secondary_color)->toBe('#FF0000');
});

test('requires a slug', function () {
    (new CreateSite)->handle(createSiteInput(['slug' => '']));
})->throws(ValidationException::class);

test('requires a unique slug', function () {
    Site::factory()->create(['slug' => 'acme-hardware']);

    (new CreateSite)->handle(createSiteInput());
})->throws(ValidationException::class);

test('requires a company name', function () {
    (new CreateSite)->handle(createSiteInput(['company_name' => '']));
})->throws(ValidationException::class);

test('rejects a malformed email', function () {
    (new CreateSite)->handle(createSiteInput(['email' => 'not-an-email']));
})->throws(ValidationException::class);

test('rejects a malformed brand color instead of throwing', function () {
    (new CreateSite)->handle(createSiteInput(['primary_color' => 'red']));
})->throws(ValidationException::class);

test('does not create a site when validation fails', function () {
    try {
        (new CreateSite)->handle(createSiteInput(['company_name' => '']));
    } catch (ValidationException) {
        // expected
    }

    expect(Site::count())->toBe(0);
});
