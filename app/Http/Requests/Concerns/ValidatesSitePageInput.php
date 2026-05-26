<?php

namespace App\Http\Requests\Concerns;

use App\Models\Site;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

trait ValidatesSitePageInput
{
    /**
     * @return array<string, array<int, ValidationRule|string>|string>
     */
    protected function sitePageRules(Site $site, ?int $ignorePageId = null): array
    {
        return [
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('site_pages', 'slug')
                    ->where('site_id', $site->id)
                    ->ignore($ignorePageId),
            ],
            'title' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ];
    }
}
