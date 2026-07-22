<?php

use App\Support\BlockCategories;

test('all categories include page sections and elements', function () {
    expect(BlockCategories::all())->toHaveCount(19);
    expect(BlockCategories::groups())->toBe([
        'Page Sections',
        'Elements',
    ]);
});

test('category slugs can be validated and resolved', function () {
    expect(BlockCategories::isValid('hero'))->toBeTrue();
    expect(BlockCategories::isValid('missing'))->toBeFalse();
    expect(BlockCategories::find('contact'))->toMatchArray([
        'slug' => 'contact',
        'name' => 'Contact Sections',
        'group' => 'Page Sections',
    ]);
    expect(BlockCategories::nameFor('hero'))->toBe('Hero Sections');
});
