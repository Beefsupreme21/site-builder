<?php

use App\Support\SiteLogo;

test('site logo resolves absolute urls unchanged', function () {
    expect(SiteLogo::url('https://cdn.example.com/logo.svg'))
        ->toBe('https://cdn.example.com/logo.svg');
});

test('site logo resolves site-root paths through asset', function () {
    expect(SiteLogo::url('/images/logo.png'))
        ->toBe(asset('images/logo.png'));
});

test('site logo returns null for empty values', function () {
    expect(SiteLogo::url(null))->toBeNull();
    expect(SiteLogo::url(''))->toBeNull();
    expect(SiteLogo::url('   '))->toBeNull();
});
