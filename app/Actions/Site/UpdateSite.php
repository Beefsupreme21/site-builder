<?php

namespace App\Actions\Site;

use App\Models\Site;
use App\Support\ColorPalette;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateSite
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function handle(Site $site, array $input): Site
    {
        $validated = Validator::make(ColorPalette::normalizeBrandColors($input), [
            'slug' => ['required', 'string', 'max:255', Rule::unique('sites', 'slug')->ignore($site->id)],
            'company_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'logo' => ['nullable', 'string', 'max:2048'],
            'primary_color' => ['required', 'string', 'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/'],
            'secondary_color' => ['required', 'string', 'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/'],
        ])->validate();

        return DB::transaction(function () use ($site, $validated): Site {
            $site->update($validated);

            return $site;
        });
    }
}
