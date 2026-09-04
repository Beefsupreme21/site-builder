<?php

use App\Models\Site;
use App\Models\Template;

test('layout show lists blocks including the content slot', function () {
    $site = Site::factory()->create();
    $layout = $site->defaultLayout();

    $this->get(route('sites.layouts.show', [$site, $layout]))
        ->assertOk()
        ->assertInertia(fn ($response) => $response
            ->component('layouts/show')
            ->has('layout.blocks', 1)
            ->where('layout.blocks.0.template.type', 'slot'));
});

test('layout block picker shows layout templates only', function () {
    $site = Site::factory()->create();
    $layout = $site->defaultLayout();

    $this->get(route('layouts.blocks.create', $layout))
        ->assertOk()
        ->assertInertia(fn ($response) => $response
            ->component('blocks/create')
            ->where('target', 'layout')
            ->has('templates', 9)
            ->where('templates.0.type', 'nav_top'));
});

test('a footer can be added to a layout', function () {
    $site = Site::factory()->create();
    $layout = $site->defaultLayout();
    $footer = Template::query()->where('type', 'simple_footer')->firstOrFail();

    $this->post(route('layouts.blocks.store', $layout), [
        'template_id' => $footer->id,
    ])->assertRedirect(route('sites.layouts.show', [$site, $layout]));

    expect($layout->blocks()->count())->toBe(2);
});

test('layout blocks can be reordered', function () {
    $site = Site::factory()->create();
    $layout = $site->defaultLayout();
    $footer = Template::query()->where('type', 'simple_footer')->firstOrFail();

    $footerBlock = $layout->blocks()->create([
        'template_id' => $footer->id,
        'content' => $footer->default_content,
        'order' => 1,
    ]);

    $this->patch(route('blocks.move', [$footerBlock, 'up']))
        ->assertRedirect(route('sites.layouts.show', [$site, $layout]));

    expect($footerBlock->fresh()->order)->toBe(0);
});

test('the slot block cannot be removed from a layout', function () {
    $site = Site::factory()->create();
    $layout = $site->defaultLayout();
    $slot = $layout->blocks()->whereHas('template', fn ($q) => $q->where('type', 'slot'))->firstOrFail();

    $this->delete(route('layouts.blocks.destroy', [$layout, $slot]))
        ->assertSessionHasErrors('block');

    expect($layout->blocks()->count())->toBe(1);
});

test('a layout block can be edited from the block editor', function () {
    $site = Site::factory()->create();
    $layout = $site->defaultLayout();
    $footer = Template::query()->where('type', 'simple_footer')->firstOrFail();

    $block = $layout->blocks()->create([
        'template_id' => $footer->id,
        'content' => '<footer>Before</footer>',
        'order' => 1,
    ]);

    $this->get(route('layouts.blocks.edit', [$layout, $block]))
        ->assertOk()
        ->assertInertia(fn ($response) => $response
            ->component('blocks/edit')
            ->where('target', 'layout')
            ->where('block.content', '<footer>Before</footer>'));

    $this->put(route('layouts.blocks.update', [$layout, $block]), [
        'content' => '<footer>After</footer>',
    ])->assertRedirect(route('sites.layouts.show', [$site, $layout]));

    expect($block->fresh()->content)->toBe('<footer>After</footer>');
});

test('preview renders layout blocks around page content at the slot', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();
    $layout = $site->defaultLayout();
    $footer = Template::query()->where('type', 'simple_footer')->firstOrFail();

    $layout->blocks()->create([
        'template_id' => $footer->id,
        'content' => $footer->default_content,
        'order' => 1,
    ]);

    $page->blocks()->create([
        'content' => '<p data-test-page-block>Page body</p>',
        'order' => 1,
    ]);

    $this->get(route('preview.show', [$site, $page]))
        ->assertOk()
        ->assertSee('Your site. All rights reserved.', false)
        ->assertSee('data-test-page-block', false);
});

test('page block picker excludes layout and system templates', function () {
    $site = Site::factory()->create();
    $page = $site->homePage();

    $this->get(route('pages.blocks.create', [$page, 'category' => 'hero']))
        ->assertOk()
        ->assertInertia(fn ($response) => $response
            ->has('templates', 6)
            ->where('templates.0.type', 'hero_centered'));
});

test('a footer block can be removed from a layout', function () {
    $site = Site::factory()->create();
    $layout = $site->defaultLayout();
    $footer = Template::query()->where('type', 'simple_footer')->firstOrFail();

    $block = $layout->blocks()->create([
        'template_id' => $footer->id,
        'content' => $footer->default_content,
        'order' => 1,
    ]);

    $this->delete(route('layouts.blocks.destroy', [$layout, $block]))
        ->assertRedirect(route('sites.layouts.show', [$site, $layout]));

    expect($layout->blocks()->count())->toBe(1);
});

test('removing a block scoped to the wrong layout returns 404', function () {
    $site = Site::factory()->create();
    $layoutA = $site->defaultLayout();
    $layoutB = $site->layouts()->create(['name' => 'Alternate']);
    $footer = Template::query()->where('type', 'simple_footer')->firstOrFail();

    $block = $layoutA->blocks()->create([
        'template_id' => $footer->id,
        'content' => $footer->default_content,
        'order' => 1,
    ]);

    $this->delete(route('layouts.blocks.destroy', [$layoutB, $block]))
        ->assertNotFound();

    expect($layoutA->blocks()->count())->toBe(2);
});

test('a layout accessed under the wrong site returns 404', function () {
    $site = Site::factory()->create();
    $other = Site::factory()->create();
    $layout = $site->defaultLayout();

    $this->get(route('sites.layouts.show', [$other, $layout]))->assertNotFound();
});

test('adding a page template to a layout flashes a validation error', function () {
    $site = Site::factory()->create();
    $layout = $site->defaultLayout();
    $hero = Template::query()->where('type', 'hero_centered')->firstOrFail();

    $this->from(route('layouts.blocks.create', $layout))
        ->post(route('layouts.blocks.store', $layout), [
            'template_id' => $hero->id,
        ])
        ->assertRedirect(route('layouts.blocks.create', $layout))
        ->assertSessionHasErrors('template_id');

    expect($layout->blocks()->count())->toBe(1);
});
