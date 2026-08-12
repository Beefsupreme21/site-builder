<?php

namespace App\Actions\SitePage;

use App\Models\SitePage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateSitePage
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function handle(SitePage $page, array $input): SitePage
    {
        $validated = Validator::make($input, [
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('site_pages', 'slug')
                    ->where('site_id', $page->site_id)
                    ->ignore($page->id),
            ],
            'title' => ['required', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ])->validate();

        return DB::transaction(function () use ($page, $validated): SitePage {
            $page->update($validated);

            return $page;
        });
    }
}
