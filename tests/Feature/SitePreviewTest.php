<?php

use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('preview renders default blade template', function () {
    $site = Site::factory()->create([
        'slug' => 'demo-dental',
        'template' => 'default',
        'company_name' => 'Demo Dental',
    ]);

    $this->get(route('sites.preview', $site))
        ->assertOk()
        ->assertViewIs('sites.templates.default')
        ->assertViewHas('site', fn ($s) => $s->is($site))
        ->assertSee('Demo Dental', false)
        ->assertSee('Template: default', false);
});

test('preview renders alternate blade template', function () {
    $site = Site::factory()->create([
        'slug' => 'demo-gym',
        'template' => 'alternate',
        'company_name' => 'Demo Gym',
    ]);

    $this->get(route('sites.preview', $site))
        ->assertOk()
        ->assertViewIs('sites.templates.alternate')
        ->assertSee('Demo Gym', false)
        ->assertSee('Our team', false);
});

test('preview falls back to default view for unknown template value', function () {
    $site = Site::factory()->create([
        'slug' => 'legacy-site',
        'template' => 'legacy-removed',
        'company_name' => 'Legacy Inc.',
    ]);

    $this->get(route('sites.preview', $site))
        ->assertOk()
        ->assertViewIs('sites.templates.default');
});

test('preview returns 404 for unknown slug', function () {
    $this->get('/preview/unknown-slug-xyz')->assertNotFound();
});
