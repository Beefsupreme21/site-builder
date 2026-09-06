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
    'element-headers anchor header' => ['element-headers', 'header_anchor'],
    'footers local business footer' => ['footers', 'footer_local'],
    'hero local business' => ['hero', 'hero_local'],
    'feature service cards' => ['feature', 'services_cards'],
    'content story split' => ['content', 'story_split'],
    'contact hours and location' => ['contact', 'hours_location'],
    'cta banner' => ['cta', 'cta_banner'],
    'element-headers practice header' => ['element-headers', 'header_practice'],
    'footers footer columns' => ['footers', 'footer_columns'],
    'hero practice' => ['hero', 'hero_practice'],
    'feature reasons grid' => ['feature', 'feature_reasons'],
    'testimonials quote cards' => ['testimonials', 'testimonial_quote'],
    'cta booking' => ['cta', 'cta_book'],
    'content mission' => ['content', 'content_mission'],
    'team grid' => ['team', 'team_grid'],
    'contact split' => ['contact', 'contact_split'],
    'element-headers studio header' => ['element-headers', 'header_studio'],
    'footers studio footer' => ['footers', 'footer_studio'],
    'hero studio' => ['hero', 'hero_studio'],
    'feature numbered services' => ['feature', 'services_list'],
    'feature service detail rows' => ['feature', 'services_detail'],
    'stats band' => ['stats', 'stats_band'],
    'cta quote' => ['cta', 'cta_quote'],
    'contact studio' => ['contact', 'contact_studio'],
    'faqs divided' => ['faqs', 'faqs_divided'],
    'faqs split' => ['faqs', 'faqs_split'],
    'faqs accordion' => ['faqs', 'faqs_accordion'],
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
    'element-headers anchor header' => ['element-headers', 'header_anchor'],
    'footers local business footer' => ['footers', 'footer_local'],
    'hero local business' => ['hero', 'hero_local'],
    'feature service cards' => ['feature', 'services_cards'],
    'content story split' => ['content', 'story_split'],
    'contact hours and location' => ['contact', 'hours_location'],
    'cta banner' => ['cta', 'cta_banner'],
    'hero practice' => ['hero', 'hero_practice'],
    'testimonials quote cards' => ['testimonials', 'testimonial_quote'],
    'team grid' => ['team', 'team_grid'],
    'contact split' => ['contact', 'contact_split'],
    'hero studio' => ['hero', 'hero_studio'],
    'feature numbered services' => ['feature', 'services_list'],
    'stats band' => ['stats', 'stats_band'],
    'contact studio' => ['contact', 'contact_studio'],
    'faqs accordion' => ['faqs', 'faqs_accordion'],
]);
