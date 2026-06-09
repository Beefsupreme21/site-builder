<?php

use App\Support\ColorPalette;

test('normalizeHex expands shorthand and uppercases', function () {
    expect(ColorPalette::normalizeHex('#abc'))->toBe('#AABBCC');
    expect(ColorPalette::normalizeHex('#1a2b3c'))->toBe('#1A2B3C');
});

test('fromHex anchors the chosen color at shade 500', function () {
    $palette = ColorPalette::fromHex('#2563EB');

    expect($palette[500])->toBe('#2563EB');
    expect($palette[50])->toBe('#EEF3FD');
    expect($palette[900])->toBe('#13337A');
    expect($palette)->toHaveKeys(ColorPalette::SHADES);
});

test('toRgba returns an rgba string', function () {
    expect(ColorPalette::toRgba('#2563EB', 0.4))->toBe('rgba(37, 99, 235, 0.4)');
});
