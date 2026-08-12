<?php

namespace App\Http\Controllers;

use App\Actions\Block\AddBlock;
use App\Actions\Block\RemoveBlock;
use App\Enums\TemplateContext;
use App\Models\Block;
use App\Models\Layout;
use App\Models\Template;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class LayoutBlockController extends Controller
{
    public function create(Layout $layout): Response
    {
        $layout->loadMissing('site');

        $templates = Template::query()
            ->where('context', TemplateContext::Layout)
            ->orderBy('id')
            ->get(['id', 'type', 'name', 'category', 'default_content']);

        return inertia('blocks/create', [
            'site' => $layout->site,
            'layout' => $layout,
            'page' => null,
            'target' => 'layout',
            'category' => 'footers',
            'activeCategory' => ['slug' => 'footers', 'name' => 'Footers', 'group' => 'Layout'],
            'categories' => [],
            'groups' => [],
            'templates' => $templates->all(),
        ]);
    }

    public function store(Layout $layout): RedirectResponse
    {
        $layout->loadMissing('site');

        (new AddBlock)->handle($layout, request()->all());

        return to_route('sites.layouts.show', [$layout->site, $layout]);
    }

    public function destroy(Layout $layout, Block $block): RedirectResponse
    {
        $layout->loadMissing('site');

        (new RemoveBlock)->handle($block);

        return to_route('sites.layouts.show', [$layout->site, $layout]);
    }
}
