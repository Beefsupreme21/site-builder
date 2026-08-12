<?php

use App\Actions\Layout\CreateDefaultLayout;
use App\Models\Site;

test('creates a default layout with a slot block', function () {
    $site = Site::create([
        'slug' => 'solo-layout',
        'company_name' => 'Solo Co',
        'primary_color' => '#111111',
        'secondary_color' => '#222222',
    ]);

    $layout = (new CreateDefaultLayout)->handle($site);

    expect($layout->name)->toBe('Default');
    expect($layout->blocks)->toHaveCount(1);
    expect($layout->blocks->first()->template->type)->toBe('slot');
});

test('accepts a custom layout name', function () {
    $site = Site::create([
        'slug' => 'named-layout',
        'company_name' => 'Named Co',
        'primary_color' => '#111111',
        'secondary_color' => '#222222',
    ]);

    $layout = (new CreateDefaultLayout)->handle($site, 'Marketing');

    expect($layout->name)->toBe('Marketing');
});
