<?php

use App\Models\Block;
use App\Models\Site;
use Database\Seeders\BlockSeeder;
use Database\Seeders\SiteSeeder;

test('new sites receive a home page named after the company', function () {
    $site = Site::factory()->create(['company_name' => 'Acme Co']);

    expect($site->pages)->toHaveCount(1);
    expect($site->homePage()?->slug)->toBe('home');
    expect($site->homePage()?->title)->toBe('Acme Co');
});

test('site show lists pages', function () {
    $site = Site::factory()->create(['company_name' => 'Acme']);

    $this->get(route('sites.show', $site))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('sites/show')
            ->has('site.pages', 1)
            ->missing('site.pages.0.block_pages'));
});

test('site stores primary and secondary colors', function () {
    $this->post(route('sites.store'), [
        'slug' => 'branded-co',
        'company_name' => 'Branded Co',
        'primary_color' => '#2563eb',
        'secondary_color' => '#abc',
    ])->assertRedirect();

    $site = Site::query()->where('slug', 'branded-co')->first();

    expect($site)->not->toBeNull();
    expect($site->primary_color)->toBe('#2563EB');
    expect($site->secondary_color)->toBe('#AABBCC');
});

test('validation failures from the action flash errors back to the form', function () {
    $this->from(route('sites.create'))
        ->post(route('sites.store'), ['slug' => '', 'company_name' => ''])
        ->assertRedirect(route('sites.create'))
        ->assertSessionHasErrors(['slug', 'company_name', 'primary_color', 'secondary_color']);

    expect(Site::count())->toBe(0);
});

test('seeded sites include brand colors', function () {
    $this->seed(SiteSeeder::class);

    $site = Site::query()->where('slug', 'blue-ocean-dental')->first();

    expect($site->primary_color)->toBe('#0284C7');
    expect($site->secondary_color)->toBe('#0F766E');
});

test('seeded sites get a home page filled with the block library', function () {
    $this->seed(BlockSeeder::class);
    $this->seed(SiteSeeder::class);

    $home = Site::query()->where('slug', 'blue-ocean-dental')->first()->homePage();

    expect($home?->slug)->toBe('home');
    expect($home->blockPages()->count())->toBe(Block::count());
});
