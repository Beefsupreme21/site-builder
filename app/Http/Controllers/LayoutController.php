<?php

namespace App\Http\Controllers;

use App\Models\Layout;
use App\Models\Site;
use Inertia\Response;

class LayoutController extends Controller
{
    public function show(Site $site, Layout $layout): Response
    {
        return inertia('layouts/show', [
            'site' => $site,
            'layout' => $layout->load(['blocks.template']),
        ]);
    }
}
