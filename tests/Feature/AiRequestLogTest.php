<?php

use App\Actions\Ai\LogAiRequest;
use App\Ai\Agents\BlockHtmlAgent;
use App\Models\AiRequestLog;
use App\Models\Site;
use Database\Seeders\TemplateSeeder;

beforeEach(function (): void {
    $this->seed(TemplateSeeder::class);
});

test('block generation logs prompt reply model tokens and skills', function () {
    BlockHtmlAgent::fake([
        ['html' => '<section class="hero">Financial hero</section>'],
    ]);

    $site = Site::factory()->create();
    $page = $site->homePage();
    $block = $page->blocks()->create([
        'content' => '<p>Before</p>',
        'order' => 1,
    ]);

    $this->postJson(route('pages.blocks.ai.store', [$page, $block]), [
        'prompt' => 'Create a financial hero section',
        'content' => '<p>Before</p>',
    ])->assertOk();

    $log = AiRequestLog::query()->sole();

    expect($log->site_id)->toBe($site->id)
        ->and($log->block_id)->toBe($block->id)
        ->and($log->agent)->toBe(BlockHtmlAgent::class)
        ->and($log->prompt)->toBe('Create a financial hero section')
        ->and($log->skills)->toBe(['refactoring-ui']);
});

test('log ai request action persists a record', function () {
    $site = Site::factory()->create();

    $log = (new LogAiRequest)->handle([
        'site_id' => $site->id,
        'agent' => BlockHtmlAgent::class,
        'provider' => 'openrouter',
        'model' => 'openrouter/free',
        'prompt' => 'Make the headline bigger',
        'reply' => '{"html":"<h1>Bigger</h1>"}',
        'prompt_tokens' => 120,
        'completion_tokens' => 80,
        'reasoning_tokens' => 0,
        'skills' => ['refactoring-ui'],
    ]);

    expect($log)->toBeInstanceOf(AiRequestLog::class)
        ->and($log->fresh()->prompt_tokens)->toBe(120)
        ->and($log->fresh()->skills)->toBe(['refactoring-ui']);
});
