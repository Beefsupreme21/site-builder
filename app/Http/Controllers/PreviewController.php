<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\SitePage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PreviewController extends Controller
{
    public function index(Site $site): RedirectResponse
    {
        $page = $site->homePage();

        if ($page === null) {
            abort(404);
        }

        return to_route('preview.show', [$site, $page]);
    }

    public function show(Site $site, SitePage $page): View
    {
        $page->load([
            'blocks',
            'layout.blocks.template',
            'site.pages',
        ]);

        return view('preview.show', [
            'site' => $page->site,
            'page' => $page,
        ]);
    }
}
