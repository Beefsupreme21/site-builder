<?php

use App\Ai\Agents\BlockHtmlAgent;
use App\Models\AiRequestLog;
use App\Models\Site;
use App\Support\Ai\LocalSkill;
use Database\Seeders\TemplateSeeder;

beforeEach(function (): void {
    $this->seed(TemplateSeeder::class);
});

test('block skill loads with reference includes', function () {
    $skill = LocalSkill::forBlockEditor();

    expect($skill['identifier'])->toBe('refactoring-ui')
        ->and($skill['instructions'])->toContain('The systems — use these, do not re-derive them')
        ->and($skill['instructions'])->toContain('--- Include: references/systems.md ---')
        ->and($skill['instructions'])->toContain('--- Include: assets/tokens.css ---');
});

test('ai endpoint returns html', function () {
    BlockHtmlAgent::fake([
        ['html' => '<section>Refactoring UI hero</section>'],
    ]);

    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = $page->blocks()->create([
        'content' => '<p>Before</p>',
        'order' => 1,
    ]);

    $this->postJson(route('pages.blocks.ai.store', [$page, $block]), [
        'prompt' => 'Give me a cool hero for my financial site',
        'content' => '<p>Before</p>',
    ])->assertOk()
        ->assertJsonPath('html', '<section>Refactoring UI hero</section>')
        ->assertJsonPath('meta.provider', 'openrouter');

    $log = AiRequestLog::query()->sole();

    expect($log->agent)->toBe(BlockHtmlAgent::class)
        ->and($log->prompt)->toBe('Give me a cool hero for my financial site')
        ->and($log->status)->toBe('succeeded')
        ->and($log->skills)->toBe(['refactoring-ui']);
});

test('failed ai request is logged', function () {
    BlockHtmlAgent::fake([
        fn () => throw new RuntimeException('Provider unavailable'),
    ]);

    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = $page->blocks()->create([
        'content' => '<p>Before</p>',
        'order' => 1,
    ]);

    $this->postJson(route('pages.blocks.ai.store', [$page, $block]), [
        'prompt' => 'Give me a cool hero',
        'content' => '<p>Before</p>',
    ])->assertStatus(500)
        ->assertJsonPath('message', 'Block generation failed. Check ai_request_logs for details.');

    $log = AiRequestLog::query()->sole();

    expect($log->status)->toBe('failed')
        ->and($log->skills)->toContain('refactoring-ui');
});

test('ai validates prompt', function () {
    BlockHtmlAgent::fake([]);

    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = $page->blocks()->create([
        'content' => '<p>Before</p>',
        'order' => 1,
    ]);

    $this->postJson(route('pages.blocks.ai.store', [$page, $block]), [
        'content' => '<p>Before</p>',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors('prompt');
});

test('empty html from model fails ai request', function () {
    BlockHtmlAgent::fake([
        ['html' => ''],
        ['html' => ''],
    ]);

    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = $page->blocks()->create([
        'content' => '<p>Before</p>',
        'order' => 1,
    ]);

    $this->postJson(route('pages.blocks.ai.store', [$page, $block]), [
        'prompt' => 'Hero section',
        'content' => '<p>Before</p>',
    ])->assertStatus(500);

    expect(AiRequestLog::query()->sole()->status)->toBe('failed');
});

test('slot blocks cannot use ai endpoint', function () {
    BlockHtmlAgent::fake([]);

    $layout = Site::factory()->create()->defaultLayout();
    $slot = $layout->blocks()
        ->whereHas('template', fn ($q) => $q->where('type', 'slot'))
        ->firstOrFail();

    $this->postJson(route('layouts.blocks.ai.store', [$layout, $slot]), [
        'prompt' => 'Hero',
        'content' => '<p>Before</p>',
    ])->assertNotFound();
});
