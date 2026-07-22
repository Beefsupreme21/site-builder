<?php

namespace App\Support;

class BlockCategories
{
    public const GROUP_PAGE_SECTIONS = 'Page Sections';

    public const GROUP_ELEMENTS = 'Elements';

    /**
     * @var list<array{slug: string, name: string, group: string}>
     */
    private const CATEGORIES = [
        ['slug' => 'hero', 'name' => 'Hero Sections', 'group' => self::GROUP_PAGE_SECTIONS],
        ['slug' => 'feature', 'name' => 'Feature Sections', 'group' => self::GROUP_PAGE_SECTIONS],
        ['slug' => 'cta', 'name' => 'CTA Sections', 'group' => self::GROUP_PAGE_SECTIONS],
        ['slug' => 'bento', 'name' => 'Bento Grids', 'group' => self::GROUP_PAGE_SECTIONS],
        ['slug' => 'pricing', 'name' => 'Pricing Sections', 'group' => self::GROUP_PAGE_SECTIONS],
        ['slug' => 'header', 'name' => 'Header Sections', 'group' => self::GROUP_PAGE_SECTIONS],
        ['slug' => 'newsletter', 'name' => 'Newsletter Sections', 'group' => self::GROUP_PAGE_SECTIONS],
        ['slug' => 'stats', 'name' => 'Stats', 'group' => self::GROUP_PAGE_SECTIONS],
        ['slug' => 'testimonials', 'name' => 'Testimonials', 'group' => self::GROUP_PAGE_SECTIONS],
        ['slug' => 'blog', 'name' => 'Blog Sections', 'group' => self::GROUP_PAGE_SECTIONS],
        ['slug' => 'contact', 'name' => 'Contact Sections', 'group' => self::GROUP_PAGE_SECTIONS],
        ['slug' => 'team', 'name' => 'Team Sections', 'group' => self::GROUP_PAGE_SECTIONS],
        ['slug' => 'content', 'name' => 'Content Sections', 'group' => self::GROUP_PAGE_SECTIONS],
        ['slug' => 'logo-clouds', 'name' => 'Logo Clouds', 'group' => self::GROUP_PAGE_SECTIONS],
        ['slug' => 'faqs', 'name' => 'FAQs', 'group' => self::GROUP_PAGE_SECTIONS],
        ['slug' => 'footers', 'name' => 'Footers', 'group' => self::GROUP_PAGE_SECTIONS],
        ['slug' => 'element-headers', 'name' => 'Headers', 'group' => self::GROUP_ELEMENTS],
        ['slug' => 'flyout-menus', 'name' => 'Flyout Menus', 'group' => self::GROUP_ELEMENTS],
        ['slug' => 'banners', 'name' => 'Banners', 'group' => self::GROUP_ELEMENTS],
    ];

    /**
     * @return list<array{slug: string, name: string, group: string}>
     */
    public static function all(): array
    {
        return self::CATEGORIES;
    }

    /**
     * @return list<string>
     */
    public static function groups(): array
    {
        return [
            self::GROUP_PAGE_SECTIONS,
            self::GROUP_ELEMENTS,
        ];
    }

    public static function isValid(string $slug): bool
    {
        return collect(self::CATEGORIES)->contains('slug', $slug);
    }

    /**
     * @return array{slug: string, name: string, group: string}|null
     */
    public static function find(string $slug): ?array
    {
        return collect(self::CATEGORIES)->firstWhere('slug', $slug);
    }

    public static function nameFor(string $slug): string
    {
        $category = self::find($slug);

        return $category['name'] ?? $slug;
    }
}
