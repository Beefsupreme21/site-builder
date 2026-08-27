<?php

use App\Ai\Agents\BlockVariantAgent;
use App\Models\AiRequestLog;
use App\Models\Site;
use App\Support\Ai\LocalSkill;
use App\Support\Ai\ResolvedAiConfig;
use App\Support\Ai\SkillRegistry;
use Database\Seeders\TemplateSeeder;

beforeEach(function (): void {
    $this->seed(TemplateSeeder::class);
});

test('local prototype skill is vendored', function () {
    $skill = LocalSkill::load('emilkowalski', 'prototype');

    expect($skill['identifier'])->toBe('emilkowalski/prototype')
        ->and($skill['instructions'])->toContain('Prototyping Variants')
        ->and($skill['picker'])->toContain('proto-picker');
});

test('vendored skills load from registry', function () {
    expect(LocalSkill::loadByKey('refactoring-ui')['identifier'])->toBe('refactoring-ui')
        ->and(LocalSkill::loadByKey('apple-design')['instructions'])->not->toBeEmpty()
        ->and(LocalSkill::loadWithBundles('animate')['bundled_identifiers'])->toBe(['emilkowalski/animation-vocabulary']);
});

test('prototype palette has four skill slots', function () {
    $palette = SkillRegistry::prototypePalette();

    expect($palette)->toHaveCount(4)
        ->and($palette[0]['skill'])->toBe('refactoring-ui')
        ->and($palette[2]['skill'])->toBe('animate');
});

test('single variant endpoint returns one variant', function () {
    BlockVariantAgent::fake([
        ['html' => '<section>Refactoring UI hero</section>'],
    ]);

    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = $page->blocks()->create([
        'content' => '<p>Before</p>',
        'order' => 1,
    ]);

    $this->postJson(route('pages.blocks.variants.store', [$page, $block, 'refactoring-ui']), [
        'prompt' => 'Give me a cool hero for my financial site',
        'content' => '<p>Before</p>',
    ])->assertOk()
        ->assertJsonPath('variant.name', 'Refactoring UI')
        ->assertJsonPath('variant.skill', 'refactoring-ui')
        ->assertJsonPath('variant.html', '<section>Refactoring UI hero</section>')
        ->assertJsonPath('meta.provider', 'openrouter');

    $log = AiRequestLog::query()->sole();

    expect($log->agent)->toBe(BlockVariantAgent::class)
        ->and($log->prompt)->toBe('Give me a cool hero for my financial site')
        ->and($log->status)->toBe('succeeded')
        ->and($log->skills)->toBe(['refactoring-ui']);
});

test('block skills are configured for the editor dropdown', function () {
    $skills = SkillRegistry::blockSkills();

    expect($skills)->not->toBeEmpty()
        ->and(collect($skills)->pluck('skill')->all())->toContain('refactoring-ui', 'prototype');
});

test('variant endpoint accepts a chosen model', function () {
    BlockVariantAgent::fake([
        ['html' => '<section>Lightning hero</section>'],
    ]);

    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = $page->blocks()->create([
        'content' => '<p>Before</p>',
        'order' => 1,
    ]);

    $this->postJson(route('pages.blocks.variants.store', [$page, $block, 'refactoring-ui']), [
        'prompt' => 'Give me a cool hero for my financial site',
        'content' => '<p>Before</p>',
        'model' => 'nvidia/nemotron-3.5-lightning:free',
    ])->assertOk()
        ->assertJsonPath('meta.model', 'nvidia/nemotron-3.5-lightning:free');
});

test('variant rejects unknown model', function () {
    BlockVariantAgent::fake([]);

    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = $page->blocks()->create([
        'content' => '<p>Before</p>',
        'order' => 1,
    ]);

    $this->postJson(route('pages.blocks.variants.store', [$page, $block, 'refactoring-ui']), [
        'prompt' => 'Hero',
        'content' => '<p>Before</p>',
        'model' => 'anthropic/claude-opus-4',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors('model');
});

test('block models are configured for the editor dropdown', function () {
    $models = ResolvedAiConfig::blockModels();

    expect($models)->not->toBeEmpty()
        ->and(collect($models)->pluck('model')->all())->toContain('openrouter/free', 'nvidia/nemotron-3.5-lightning:free');
});

test('unknown block skill returns 404', function () {
    BlockVariantAgent::fake([]);

    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = $page->blocks()->create([
        'content' => '<p>Before</p>',
        'order' => 1,
    ]);

    $this->postJson(route('pages.blocks.variants.store', [$page, $block, 'not-a-skill']), [
        'prompt' => 'Hero',
        'content' => '<p>Before</p>',
    ])->assertNotFound();
});

test('failed variant request is logged per call', function () {
    BlockVariantAgent::fake([
        fn () => throw new RuntimeException('Provider unavailable'),
    ]);

    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = $page->blocks()->create([
        'content' => '<p>Before</p>',
        'order' => 1,
    ]);

    $this->postJson(route('pages.blocks.variants.store', [$page, $block, 'apple-design']), [
        'prompt' => 'Give me a cool hero',
        'content' => '<p>Before</p>',
    ])->assertStatus(500)
        ->assertJsonPath('message', 'Variant generation failed. Check ai_request_logs for details.');

    $log = AiRequestLog::query()->sole();

    expect($log->status)->toBe('failed')
        ->and($log->error_message)->toContain('apple-design')
        ->and($log->skills)->toContain('emilkowalski/apple-design');
});

test('variant validates prompt', function () {
    BlockVariantAgent::fake([]);

    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = $page->blocks()->create([
        'content' => '<p>Before</p>',
        'order' => 1,
    ]);

    $this->postJson(route('pages.blocks.variants.store', [$page, $block, 'refactoring-ui']), [
        'content' => '<p>Before</p>',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors('prompt');
});

test('empty html from model fails variant request', function () {
    BlockVariantAgent::fake([
        ['html' => ''],
        ['html' => ''],
    ]);

    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = $page->blocks()->create([
        'content' => '<p>Before</p>',
        'order' => 1,
    ]);

    $this->postJson(route('pages.blocks.variants.store', [$page, $block, 'animate']), [
        'prompt' => 'Animated hero',
        'content' => '<p>Before</p>',
    ])->assertStatus(500);

    expect(AiRequestLog::query()->sole()->status)->toBe('failed');
});
