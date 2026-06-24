<?php

use App\Support\ColorPalette;

test('normalizeHex expands shorthand and uppercases', function () {
    expect(ColorPalette::normalizeHex('#abc'))->toBe('#AABBCC');
    expect(ColorPalette::normalizeHex('#1a2b3c'))->toBe('#1A2B3C');
});
