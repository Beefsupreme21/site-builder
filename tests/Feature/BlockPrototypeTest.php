<?php

use App\Ai\Agents\BlockPrototypeAgent;
use App\Models\AiRequestLog;
use App\Models\Site;
use App\Support\Ai\LocalSkill;
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

test('block prototype returns four variants', function () {
    BlockPrototypeAgent::fake([
        [
            'variants' => [
                ['name' => 'Quiet', 'axis' => 'Minimal motion', 'html' => '<section>1</section>'],
                ['name' => 'Editorial', 'axis' => 'Large type', 'html' => '<section>2</section>'],
                ['name' => 'Bold', 'axis' => 'High contrast', 'html' => '<section>3</section>'],
                ['name' => 'Playful', 'axis' => 'Rounded shapes', 'html' => '<section>4</section>'],
            ],
        ],
    ]);

    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = $page->blocks()->create([
        'content' => '<p>Before</p>',
        'order' => 1,
    ]);

    $this->postJson(route('pages.blocks.prototype', [$page, $block]), [
        'prompt' => 'Give me a cool hero for my financial site',
        'content' => '<p>Before</p>',
    ])->assertOk()
        ->assertJsonCount(4, 'variants')
        ->assertJsonPath('variants.0.name', 'Quiet')
        ->assertJsonPath('meta.provider', 'openrouter');

    $log = AiRequestLog::query()->sole();

    expect($log->skills)->toBe(['emilkowalski/prototype'])
        ->and($log->prompt)->toBe('Give me a cool hero for my financial site')
        ->and($log->status)->toBe('succeeded');
});

test('failed prototype requests are logged', function () {
    BlockPrototypeAgent::fake([
        fn () => throw new RuntimeException('Provider unavailable'),
    ]);

    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = $page->blocks()->create([
        'content' => '<p>Before</p>',
        'order' => 1,
    ]);

    $this->postJson(route('pages.blocks.prototype', [$page, $block]), [
        'prompt' => 'Give me a cool hero',
        'content' => '<p>Before</p>',
    ])->assertStatus(500)
        ->assertJsonPath('message', 'Prototype generation failed. Check ai_request_logs for details.');

    $log = AiRequestLog::query()->sole();

    expect($log->status)->toBe('failed')
        ->and($log->error_message)->toBe('Provider unavailable')
        ->and($log->provider)->toBe('openrouter');
});

test('block prototype validates prompt', function () {
    BlockPrototypeAgent::fake([]);

    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = $page->blocks()->create([
        'content' => '<p>Before</p>',
        'order' => 1,
    ]);

    $this->postJson(route('pages.blocks.prototype', [$page, $block]), [
        'content' => '<p>Before</p>',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors('prompt');
});
