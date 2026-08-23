<?php

use App\Support\BlockTemplateView;

test('block template view names use category folders', function () {
    expect(BlockTemplateView::name('hero', 'hero_centered'))
        ->toBe('blocks.hero.hero_centered');
});

test('seeded block templates have blade views', function (string $category, string $type) {
    expect(BlockTemplateView::exists($category, $type))->toBeTrue();
})->with([
    'system slot' => ['system', 'slot'],
    'element-headers nav top' => ['element-headers', 'nav_top'],
    'footers simple footer' => ['footers', 'simple_footer'],
    'footers footer social' => ['footers', 'footer_social'],
    'hero centered' => ['hero', 'hero_centered'],
    'hero image' => ['hero', 'hero_image'],
    'split screenshot' => ['hero', 'split_screenshot'],
    'content simple' => ['content', 'content_simple'],
    'content split' => ['content', 'content_split'],
    'contact form' => ['contact', 'contact_form'],
    'newsletter side by side' => ['newsletter', 'newsletter_side_by_side'],
    'newsletter side by side brand' => ['newsletter', 'newsletter_side_by_side_brand'],
    'newsletter centered card' => ['newsletter', 'newsletter_centered_card'],
]);

test('seeded block templates render html', function (string $category, string $type) {
    expect(BlockTemplateView::render($category, $type))
        ->not->toBeEmpty();
})->with([
    'element-headers nav top' => ['element-headers', 'nav_top'],
    'footers simple footer' => ['footers', 'simple_footer'],
    'footers footer social' => ['footers', 'footer_social'],
    'hero centered' => ['hero', 'hero_centered'],
    'contact form' => ['contact', 'contact_form'],
    'newsletter centered card' => ['newsletter', 'newsletter_centered_card'],
]);
