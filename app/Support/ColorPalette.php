<?php

namespace App\Support;

use InvalidArgumentException;

class ColorPalette
{
    /**
     * The brand color fields carried on a site.
     *
     * @var list<string>
     */
    public const BRAND_FIELDS = ['primary_color', 'secondary_color'];

    /**
     * Expand and upper-case any brand colors present, leaving malformed values
     * untouched so the validator reports them instead of throwing.
     *
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public static function normalizeBrandColors(array $input): array
    {
        foreach (self::BRAND_FIELDS as $field) {
            $value = $input[$field] ?? null;

            if (is_string($value) && self::isHex($value)) {
                $input[$field] = self::normalizeHex($value);
            }
        }

        return $input;
    }

    public static function isHex(string $hex): bool
    {
        return (bool) preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', trim($hex));
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
}
