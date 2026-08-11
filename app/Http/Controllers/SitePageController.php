<?php

namespace App\Http\Controllers;

use App\Actions\SitePage\CreateSitePage;
use App\Actions\SitePage\DeleteSitePage;
use App\Actions\SitePage\UpdateSitePage;
use App\Models\Site;
use App\Models\SitePage;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class SitePageController extends Controller
{
    public function create(Site $site): Response
    {
        return inertia('site-pages/create', [
            'site' => $site,
            'nextSortOrder' => (int) $site->pages()->max('sort_order') + 1,
        ]);
    }

    public function store(Site $site): RedirectResponse
    {
        (new CreateSitePage)->handle($site, request()->all());

        return to_route('sites.show', $site);
    }

    public function show(Site $site, SitePage $page): Response
    {
        return inertia('site-pages/show', [
            'site' => $site,
            'page' => $page->load('blockPages'),
        ]);
    }

    public function edit(Site $site, SitePage $page): Response
    {
        return inertia('site-pages/edit', [
            'site' => $site,
            'page' => $page,
        ]);
    }

    public function update(Site $site, SitePage $page): RedirectResponse
    {
        (new UpdateSitePage)->handle($page, request()->all());

        return to_route('sites.pages.show', [$site, $page]);
    }

    public function destroy(Site $site, SitePage $page): RedirectResponse
    {
        (new DeleteSitePage)->handle($page);

        return to_route('sites.show', $site);
    }
}
