<?php

namespace App\Support;

use App\Models\Site;
use Illuminate\Support\Facades\View;

class BlockTemplateView
{
    public static function name(string $category, string $type): string
    {
        return "blocks.{$category}.{$type}";
    }

    public static function exists(string $category, string $type): bool
    {
        return View::exists(self::name($category, $type));
    }

    public static function render(string $category, string $type, ?Site $site = null): string
    {
        return View::make(self::name($category, $type), [
            'site' => $site,
        ])->render();
    }
}
