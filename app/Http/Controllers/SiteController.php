<?php

namespace App\Http\Controllers;

use App\Actions\Site\CreateSite;
use App\Actions\Site\DeleteSite;
use App\Actions\Site\UpdateSite;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class SiteController extends Controller
{
    public function index(): Response
    {
        return inertia('sites/index', [
            'sites' => Site::query()->orderBy('company_name')->get(),
        ]);
    }

    public function create(): Response
    {
        return inertia('sites/create');
    }

    public function store(): RedirectResponse
    {
        $site = (new CreateSite)->handle(request()->all());

        return to_route('sites.show', $site);
    }

    public function show(Site $site): Response
    {
        $site->load('pages');

        return inertia('sites/show', [
            'site' => $site,
            'defaultLayout' => $site->defaultLayout(),
        ]);
    }

    public function edit(Site $site): Response
    {
        return inertia('sites/edit', [
            'site' => $site,
        ]);
    }

    public function update(Site $site): RedirectResponse
    {
        (new UpdateSite)->handle($site, request()->all());

        return to_route('sites.show', $site);
    }

    public function destroy(Site $site): RedirectResponse
    {
        (new DeleteSite)->handle($site);

        return to_route('sites.index');
    }
}
