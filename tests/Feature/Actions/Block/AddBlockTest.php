<?php

use App\Actions\Block\AddBlock;
use App\Enums\TemplateContext;
use App\Models\Site;
use App\Models\Template;
use Illuminate\Validation\ValidationException;

test('adds a block to the page using the library default content', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();
    $template = Template::create([
        'context' => TemplateContext::Page,
        'name' => 'Hero',
        'category' => 'hero',
        'type' => 'test_hero',
        'default_content' => '<section>Hero</section>',
    ]);

    $block = (new AddBlock)->handle($page, ['template_id' => $template->id]);

    expect($block->content)->toBe('<section>Hero</section>');
    expect($block->blockable_id)->toBe($page->id);
    expect($block->blockable_type)->toBe($page->getMorphClass());
});

test('appends each block after the current highest order', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();
    $template = Template::create([
        'context' => TemplateContext::Page,
        'name' => 'Hero',
        'category' => 'hero',
        'type' => 'test_hero_order',
        'default_content' => '<section>Hero</section>',
    ]);

    $first = (new AddBlock)->handle($page, ['template_id' => $template->id]);
    $second = (new AddBlock)->handle($page, ['template_id' => $template->id]);

    expect($first->order)->toBe(1);
    expect($second->order)->toBe(2);
});

test('rejects layout templates on pages', function () {
    $page = Site::factory()->create()->homePage();
    $template = Template::query()->where('type', 'simple_footer')->firstOrFail();

    (new AddBlock)->handle($page, ['template_id' => $template->id]);
})->throws(ValidationException::class);

test('requires a template id', function () {
    (new AddBlock)->handle(Site::factory()->create()->homePage(), []);
})->throws(ValidationException::class);

test('requires the template to exist in the library', function () {
    (new AddBlock)->handle(Site::factory()->create()->homePage(), ['template_id' => 9999]);
})->throws(ValidationException::class);

test('does not add a block when validation fails', function () {
    $page = Site::factory()->create()->homePage();

    try {
        (new AddBlock)->handle($page, ['template_id' => 9999]);
    } catch (ValidationException) {
        // expected
    }

    expect($page->blocks()->count())->toBe(0);
});
