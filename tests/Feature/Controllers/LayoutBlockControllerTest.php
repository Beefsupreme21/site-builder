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
            ->has('templates', 1)
            ->where('templates.0.type', 'simple_footer'));
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

    $this->get(route('preview.show', $page))
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
            ->has('templates', 3)
            ->where('templates.0.type', 'hero_centered'));
});
