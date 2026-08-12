<?php

use App\Models\Site;
use App\Models\Template;
use Database\Seeders\TemplateSeeder;

beforeEach(function (): void {
    $this->seed(TemplateSeeder::class);
});

test('block library is seeded with the starter blocks', function () {
    expect(Template::query()->orderBy('id')->pluck('type')->all())
        ->toBe([
            'slot',
            'simple_footer',
            'hero_centered',
            'hero_image',
            'split_screenshot',
            'content_simple',
            'content_split',
            'contact_form',
        ]);
});

test('a block can be added to a page from the library', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();
    $template = Template::query()->where('type', 'hero_centered')->firstOrFail();

    $this->post(route('pages.blocks.store', $page), [
        'template_id' => $template->id,
    ])->assertRedirect(route('sites.pages.show', [$site, $page]));

    expect($page->blocks()->count())->toBe(1);
    expect($page->blocks()->first()->content)
        ->toBe($template->default_content);
});

test('blocks order increments per add', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();
    $hero = Template::query()->where('type', 'hero_centered')->firstOrFail();
    $content = Template::query()->where('type', 'content_simple')->firstOrFail();

    $this->post(route('pages.blocks.store', $page), ['template_id' => $hero->id]);
    $this->post(route('pages.blocks.store', $page), ['template_id' => $content->id]);

    expect($page->blocks()->orderBy('order')->pluck('order')->all())
        ->toBe([1, 2]);
});

test('a block can be removed from a page', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = $page->blocks()->create([
        'content' => '<p>Removable</p>',
        'order' => 1,
    ]);

    $this->delete(route('pages.blocks.destroy', [$page, $block]))
        ->assertRedirect(route('sites.pages.show', [$site, $page]));

    expect($page->blocks()->count())->toBe(0);
});

test('removing a block scoped to the wrong page returns 404', function () {
    $site = Site::factory()->create();
    $pageA = $site->homePage();
    $pageB = $site->pages()->create([
        'slug' => 'other',
        'title' => 'Other',
        'order' => 1,
        'layout_id' => $site->defaultLayout()->id,
    ]);
    $block = $pageA->blocks()->create([
        'content' => '<p>Belongs to pageA</p>',
        'order' => 1,
    ]);

    $this->delete(route('pages.blocks.destroy', [$pageB, $block]))
        ->assertNotFound();

    expect($pageA->blocks()->count())->toBe(1);
});

test('a block can be edited from the block editor', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = $page->blocks()->create([
        'content' => '<p>Before</p>',
        'order' => 1,
    ]);

    $this->get(route('pages.blocks.edit', [$page, $block]))
        ->assertOk()
        ->assertInertia(fn ($response) => $response
            ->component('blocks/edit')
            ->where('target', 'page')
            ->where('block.content', '<p>Before</p>'));

    $this->put(route('pages.blocks.update', [$page, $block]), [
        'content' => '<p>After</p>',
    ])->assertRedirect(route('sites.pages.show', [$site, $page]));

    expect($block->fresh()->content)->toBe('<p>After</p>');
});

test('the slot block cannot be opened in the editor', function () {
    $layout = Site::factory()->create()->defaultLayout();
    $slot = $layout->blocks()
        ->whereHas('template', fn ($q) => $q->where('type', 'slot'))
        ->firstOrFail();

    $this->get(route('layouts.blocks.edit', [$layout, $slot]))->assertNotFound();
});

test('block library picker shows section categories by default', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();

    $this->get(route('pages.blocks.create', $page))
        ->assertOk()
        ->assertInertia(fn ($response) => $response
            ->component('blocks/create')
            ->where('category', null)
            ->has('categories', 19)
            ->has('groups', 2)
            ->where('categories.0.slug', 'hero')
            ->where('categories.0.count', 3)
            ->has('templates', 0));
});

test('block library picker filters blocks by category', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();

    $this->get(route('pages.blocks.create', [$page, 'category' => 'hero']))
        ->assertOk()
        ->assertInertia(fn ($response) => $response
            ->component('blocks/create')
            ->where('category', 'hero')
            ->where('activeCategory.name', 'Hero Sections')
            ->has('templates', 3)
            ->where('templates.0.type', 'hero_centered')
            ->where('templates.0.name', 'Hero Centered'));

    $this->get(route('pages.blocks.create', [$page, 'category' => 'content']))
        ->assertOk()
        ->assertInertia(fn ($response) => $response
            ->has('templates', 2)
            ->where('templates.0.type', 'content_simple'));

    $this->get(route('pages.blocks.create', [$page, 'category' => 'feature']))
        ->assertOk()
        ->assertInertia(fn ($response) => $response
            ->has('templates', 0));
});

test('invalid block category shows the section index', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();

    $this->get(route('pages.blocks.create', [$page, 'category' => 'not-real']))
        ->assertOk()
        ->assertInertia(fn ($response) => $response
            ->where('category', null)
            ->has('templates', 0));
});

test('adding a layout template to a page flashes a validation error', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();
    $footer = Template::query()->where('type', 'simple_footer')->firstOrFail();

    $this->from(route('pages.blocks.create', $page))
        ->post(route('pages.blocks.store', $page), [
            'template_id' => $footer->id,
        ])
        ->assertRedirect(route('pages.blocks.create', $page))
        ->assertSessionHasErrors('template_id');

    expect($page->blocks()->count())->toBe(0);
});

test('adding a block with an invalid template id flashes a validation error', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();

    $this->from(route('pages.blocks.create', $page))
        ->post(route('pages.blocks.store', $page), [
            'template_id' => 9999,
        ])
        ->assertRedirect(route('pages.blocks.create', $page))
        ->assertSessionHasErrors('template_id');
});
