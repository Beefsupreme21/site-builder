<?php

namespace App\Http\Requests\Concerns;

use App\Models\Site;
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
            'template' => ['nullable', 'string', Rule::in(Site::TEMPLATES)],
            'company_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'logo' => ['nullable', 'string', 'max:2048'],
        ];
    }

    /**
     * Ensure template is never stored empty; database column is non-nullable.
     */
    protected function normalizeTemplateInput(): void
    {
        if (! filled($this->input('template'))) {
            $this->merge(['template' => 'default']);
        }
    }
}
