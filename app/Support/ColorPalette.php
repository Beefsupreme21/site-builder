<?php

namespace App\Support;

use InvalidArgumentException;

class ColorPalette
{
    public const DEFAULT_PRIMARY = '#171717';

    public const DEFAULT_SECONDARY = '#525252';

    /** @var list<int> */
    public const SHADES = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950];

    /** @var array<int, float> Amount of white mixed in (lighter shades). */
    private const WHITE_MIX = [
        50 => 0.92,
        100 => 0.84,
        200 => 0.68,
        300 => 0.52,
        400 => 0.36,
    ];

    /** @var array<int, float> Amount of black mixed in (darker shades). */
    private const BLACK_MIX = [
        600 => 0.10,
        700 => 0.18,
        800 => 0.32,
        900 => 0.48,
        950 => 0.62,
    ];

    /**
     * Build a shade scale from a hex color. The input hex is always shade 500.
     *
     * @return array<int, string>
     */
    public static function fromHex(string $hex): array
    {
        $hex = self::normalizeHex($hex);
        $palette = [500 => $hex];

        foreach (self::WHITE_MIX as $shade => $weight) {
            $palette[$shade] = self::mix($hex, '#FFFFFF', $weight);
        }

        foreach (self::BLACK_MIX as $shade => $weight) {
            $palette[$shade] = self::mix($hex, '#000000', $weight);
        }

        ksort($palette);

        return $palette;
    }

    public static function toRgba(string $hex, float $alpha): string
    {
        $hex = ltrim(self::normalizeHex($hex), '#');

        $red = hexdec(substr($hex, 0, 2));
        $green = hexdec(substr($hex, 2, 2));
        $blue = hexdec(substr($hex, 4, 2));

        return sprintf('rgba(%d, %d, %d, %s)', $red, $green, $blue, rtrim(rtrim(number_format($alpha, 2, '.', ''), '0'), '.'));
    }

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

    private static function mix(string $hex, string $mixHex, float $weight): string
    {
        $weight = max(0.0, min(1.0, $weight));
        $base = self::hexToRgb($hex);
        $mix = self::hexToRgb($mixHex);

        return sprintf(
            '#%02X%02X%02X',
            (int) round($base[0] * (1 - $weight) + $mix[0] * $weight),
            (int) round($base[1] * (1 - $weight) + $mix[1] * $weight),
            (int) round($base[2] * (1 - $weight) + $mix[2] * $weight),
        );
    }

    /**
     * @return array{0: int, 1: int, 2: int}
     */
    private static function hexToRgb(string $hex): array
    {
        $hex = ltrim(self::normalizeHex($hex), '#');

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }
}
