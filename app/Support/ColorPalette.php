<?php

namespace App\Support;

use InvalidArgumentException;

class ColorPalette
{
    public static function normalizeHex(string $hex): string
    {
        $hex = trim($hex);

        if (! preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $hex, $matches)) {
            throw new InvalidArgumentException('Invalid hex color.');
        }

        $value = $matches[1];

        if (strlen($value) === 3) {
            $value = $value[0].$value[0].$value[1].$value[1].$value[2].$value[2];
        }

        return '#'.strtoupper($value);
    }
}
