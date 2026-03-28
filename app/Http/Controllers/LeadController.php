<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiteLeadRequest;
use App\Models\Lead;
use App\Models\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class LeadController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('leads/index', [
            'leads' => Lead::query()
                ->with(['site:id,company_name,slug'])
                ->latest()
                ->get(),
        ]);
    }

    /**
     * Store a lead from the public preview contact form.
     *
     * Browsers send Accept: application/json (e.g. via fetch) for an in-place response; otherwise
     * Laravel uses a redirect — standard Post/Redirect/Get for full page submissions.
     */
    public function store(StoreSiteLeadRequest $request, Site $site): JsonResponse|RedirectResponse
    {
        $site->leads()->create($request->safe()->only([
            'first_name',
            'last_name',
            'email',
            'phone',
            'message',
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Thank you — we received your message and will be in touch soon.',
            ]);
        }

        return redirect()
            ->route('sites.preview', $site)
            ->withFragment('contact')
            ->with('lead_submitted', true);
    }
}
