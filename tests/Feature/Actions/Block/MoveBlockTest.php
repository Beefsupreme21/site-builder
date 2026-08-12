<?php

use App\Actions\Block\MoveBlock;
use App\Models\Site;
use App\Models\Template;
use Illuminate\Validation\ValidationException;

function pageWithThreeBlocks(): array
{
    $page = Site::factory()->create()->homePage();

    return [
        $page->blocks()->create(['content' => '<p>One</p>', 'order' => 0]),
        $page->blocks()->create(['content' => '<p>Two</p>', 'order' => 1]),
        $page->blocks()->create(['content' => '<p>Three</p>', 'order' => 2]),
    ];
}

test('moving up swaps sort order with the block above', function () {
    [$first, $second] = pageWithThreeBlocks();

    (new MoveBlock)->handle($second, ['direction' => 'up']);

    expect($second->fresh()->order)->toBe(0);
    expect($first->fresh()->order)->toBe(1);
});

test('moving down swaps sort order with the block below', function () {
    [$first, $second] = pageWithThreeBlocks();

    (new MoveBlock)->handle($first, ['direction' => 'down']);

    expect($first->fresh()->order)->toBe(1);
    expect($second->fresh()->order)->toBe(0);
});

test('moving the first block up leaves sort order unchanged', function () {
    [$first, $second, $third] = pageWithThreeBlocks();

    (new MoveBlock)->handle($first, ['direction' => 'up']);

    expect($first->fresh()->order)->toBe(0);
    expect($second->fresh()->order)->toBe(1);
    expect($third->fresh()->order)->toBe(2);
});

test('moving the last block down leaves sort order unchanged', function () {
    [$first, $second, $third] = pageWithThreeBlocks();

    (new MoveBlock)->handle($third, ['direction' => 'down']);

    expect($first->fresh()->order)->toBe(0);
    expect($second->fresh()->order)->toBe(1);
    expect($third->fresh()->order)->toBe(2);
});

test('does not swap with a block on another page', function () {
    [$first] = pageWithThreeBlocks();
    $otherPage = Site::factory()->create()->homePage();
    $stranger = $otherPage->blocks()->create(['content' => '<p>Other</p>', 'order' => 99]);

    (new MoveBlock)->handle($first, ['direction' => 'up']);

    expect($stranger->fresh()->order)->toBe(99);
});

test('the slot block can be reordered in the layout', function () {
    $layout = Site::factory()->create()->defaultLayout();
    $slot = $layout->blocks()->whereHas('template', fn ($q) => $q->where('type', 'slot'))->firstOrFail();
    $footer = Template::query()->where('type', 'simple_footer')->firstOrFail();

    $layout->blocks()->create([
        'template_id' => $footer->id,
        'content' => $footer->default_content,
        'order' => 1,
    ]);

    (new MoveBlock)->handle($slot, ['direction' => 'down']);

    expect($slot->fresh()->order)->toBe(1);
});

test('a layout block can move past the slot using list position', function () {
    $layout = Site::factory()->create()->defaultLayout();
    $slot = $layout->blocks()->whereHas('template', fn ($q) => $q->where('type', 'slot'))->firstOrFail();
    $footer = Template::query()->where('type', 'simple_footer')->firstOrFail();

    $top = $layout->blocks()->create([
        'template_id' => $footer->id,
        'content' => '<footer data-test="top">Top</footer>',
        'order' => 0,
    ]);
    $slot->update(['order' => 1]);
    $bottom = $layout->blocks()->create([
        'template_id' => $footer->id,
        'content' => '<footer data-test="bottom">Bottom</footer>',
        'order' => 2,
    ]);

    (new MoveBlock)->handle($bottom, ['direction' => 'up']);

    expect($layout->blocks()->orderBy('order')->orderBy('id')->pluck('id')->all())
        ->toBe([$top->id, $bottom->id, $slot->id]);

    (new MoveBlock)->handle($bottom, ['direction' => 'up']);

    expect($layout->blocks()->orderBy('order')->orderBy('id')->pluck('id')->all())
        ->toBe([$bottom->id, $top->id, $slot->id]);
});

test('requires a direction', function () {
    [$first] = pageWithThreeBlocks();

    (new MoveBlock)->handle($first, []);
})->throws(ValidationException::class);

test('rejects a direction outside the enum', function () {
    [$first] = pageWithThreeBlocks();

    (new MoveBlock)->handle($first, ['direction' => 'sideways']);
})->throws(ValidationException::class);
