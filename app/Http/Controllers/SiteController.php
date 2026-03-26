<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiteRequest;
use App\Http\Requests\UpdateSiteRequest;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
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
        $site = Site::create($request->validated());

        return redirect()->route('sites.edit', $site);
    }

    public function preview(Site $site): View
    {
        return view(
            'sites.templates.'.$site->previewTemplateKey(),
            ['site' => $site],
        );
    }

    public function edit(Site $site): Response
    {
        return Inertia::render('sites/edit', [
            'site' => $site,
        ]);
    }

    public function update(UpdateSiteRequest $request, Site $site): RedirectResponse
    {
        $site->update($request->validated());

        return redirect()->route('sites.edit', $site);
    }

    public function destroy(Site $site): RedirectResponse
    {
        $site->delete();

        return redirect()->route('sites.index');
    }
}
