<?php

namespace App\Actions\SitePage;

use App\Models\Site;
use App\Models\SitePage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CreateSitePage
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function handle(Site $site, array $input): SitePage
    {
        $validated = Validator::make($input, [
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('site_pages', 'slug')->where('site_id', $site->id),
            ],
            'title' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ])->validate();

        return DB::transaction(function () use ($site, $validated): SitePage {
            $validated['sort_order'] ??= (int) $site->pages()->max('sort_order') + 1;

            return $site->pages()->create($validated);
        });
    }
}
