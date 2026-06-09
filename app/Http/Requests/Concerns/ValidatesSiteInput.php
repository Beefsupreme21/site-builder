<?php

namespace App\Http\Requests\Concerns;

use App\Support\ColorPalette;
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
            'primary_color' => ['required', 'string', 'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/'],
            'secondary_color' => ['required', 'string', 'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/'],
        ];
    }

    protected function prepareSiteInput(): void
    {
        $normalized = [];

        foreach (['primary_color', 'secondary_color'] as $field) {
            $value = $this->input($field);

            if (is_string($value) && $value !== '') {
                $normalized[$field] = ColorPalette::normalizeHex($value);
            }
        }

        if ($normalized !== []) {
            $this->merge($normalized);
        }
    }
}
