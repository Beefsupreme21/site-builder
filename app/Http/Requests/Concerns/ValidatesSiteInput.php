<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

trait ValidatesSiteInput
{
    /**
     * Shared validation rules for creating or updating a site.
     *
     * @return array<string, array<int, ValidationRule|string>|string>
     */
    protected function siteRules(?int $ignoreSiteId = null): array
    {
        return [
            'slug' => [
                'required',
                'string',
                'max:255',
                $ignoreSiteId !== null
                    ? Rule::unique('sites', 'slug')->ignore($ignoreSiteId)
                    : Rule::unique('sites', 'slug'),
            ],
            'company_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'logo' => ['nullable', 'string', 'max:2048'],
        ];
    }
}
