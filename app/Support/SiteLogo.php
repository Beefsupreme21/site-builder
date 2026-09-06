<?php

namespace App\Support;

class SiteLogo
{
    public static function url(?string $logo): ?string
    {
        if ($logo === null || trim($logo) === '') {
            return null;
        }

        $logo = trim($logo);

        if (filter_var($logo, FILTER_VALIDATE_URL)) {
            return $logo;
        }

        return asset(ltrim($logo, '/'));
    }
}
