<?php

use App\Actions\Ai\LogAiRequest;
use App\Ai\Agents\BlockPrototypeAgent;
use App\Models\AiRequestLog;
use App\Models\Site;
use Database\Seeders\TemplateSeeder;

beforeEach(function (): void {
    $this->seed(TemplateSeeder::class);
});

test('prototype generation logs prompt reply model tokens and skills', function () {
    BlockPrototypeAgent::fake([
        [
            'variants' => [
                ['name' => 'Quiet', 'axis' => 'Minimal', 'html' => '<section>1</section>'],
                ['name' => 'Editorial', 'axis' => 'Large type', 'html' => '<section>2</section>'],
                ['name' => 'Bold', 'axis' => 'Contrast', 'html' => '<section>3</section>'],
                ['name' => 'Playful', 'axis' => 'Rounded', 'html' => '<section>4</section>'],
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
        'prompt' => 'Create a financial hero section',
        'content' => '<p>Before</p>',
    ])->assertOk();

    $log = AiRequestLog::query()->sole();

    expect($log->site_id)->toBe($site->id)
        ->and($log->block_id)->toBe($block->id)
        ->and($log->agent)->toBe(BlockPrototypeAgent::class)
        ->and($log->prompt)->toBe('Create a financial hero section')
        ->and($log->skills)->toBe(['emilkowalski/prototype']);
});

test('log ai request action persists a record', function () {
    $site = Site::factory()->create();

    $log = (new LogAiRequest)->handle([
        'site_id' => $site->id,
        'agent' => BlockPrototypeAgent::class,
        'provider' => 'openrouter',
        'model' => 'openrouter/free',
        'prompt' => 'Make the headline bigger',
        'reply' => '[{"name":"Quiet","html":"<h1>Bigger</h1>"}]',
        'prompt_tokens' => 120,
        'completion_tokens' => 80,
        'reasoning_tokens' => 0,
        'skills' => ['emilkowalski/prototype'],
    ]);

    expect($log)->toBeInstanceOf(AiRequestLog::class)
        ->and($log->fresh()->prompt_tokens)->toBe(120)
        ->and($log->fresh()->skills)->toBe(['emilkowalski/prototype']);
});
