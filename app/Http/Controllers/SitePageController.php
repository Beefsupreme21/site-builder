<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSitePageRequest;
use App\Http\Requests\UpdateSitePageRequest;
use App\Models\Site;
use App\Models\SitePage;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SitePageController extends Controller
{
    public function create(Site $site): Response
    {
        $nextSortOrder = (int) $site->pages()->max('sort_order') + 1;

        return Inertia::render('sites/pages/create', [
            'site' => $site,
            'nextSortOrder' => $nextSortOrder,
        ]);
    }

    public function store(StoreSitePageRequest $request, Site $site): RedirectResponse
    {
        $data = $request->validated();

        if (! isset($data['sort_order'])) {
            $data['sort_order'] = (int) $site->pages()->max('sort_order') + 1;
        }

        $site->pages()->create($data);

        return redirect()->route('sites.show', $site);
    }

    public function edit(Site $site, SitePage $page): Response
    {
        return Inertia::render('sites/pages/edit', [
            'site' => $site,
            'page' => $page,
        ]);
    }

    public function update(UpdateSitePageRequest $request, Site $site, SitePage $page): RedirectResponse
    {
        $page->update($request->validated());

        return redirect()->route('sites.show', $site);
    }

    public function destroy(Site $site, SitePage $page): RedirectResponse
    {
        $page->delete();

        return redirect()->route('sites.show', $site);
    }
}
