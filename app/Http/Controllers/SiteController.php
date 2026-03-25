<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiteRequest;
use App\Http\Requests\UpdateSiteRequest;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SiteController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('sites/index', [
            'sites' => Site::query()->orderBy('company_name')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('sites/create');
    }

    public function store(StoreSiteRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['template'] = filled($validated['template'] ?? null)
            ? $validated['template']
            : 'default';

        $site = Site::create($validated);

        return redirect()->route('sites.show', $site);
    }

    public function show(Site $site): Response
    {
        return Inertia::render('sites/show', [
            'site' => $site,
        ]);
    }

    public function edit(Site $site): Response
    {
        return Inertia::render('sites/edit', [
            'site' => $site,
        ]);
    }

    public function update(UpdateSiteRequest $request, Site $site): RedirectResponse
    {
        $validated = $request->validated();
        if (array_key_exists('template', $validated) && ! filled($validated['template'])) {
            $validated['template'] = 'default';
        }

        $site->update($validated);

        return redirect()->route('sites.show', $site);
    }

    public function destroy(Site $site): RedirectResponse
    {
        $site->delete();

        return redirect()->route('sites.index');
    }
}
