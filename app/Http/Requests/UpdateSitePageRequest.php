<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidatesSitePageInput;
use App\Models\Site;
use App\Models\SitePage;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSitePageRequest extends FormRequest
{
    use ValidatesSitePageInput;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Site $site */
        $site = $this->route('site');

        /** @var SitePage $page */
        $page = $this->route('page');

        return $this->sitePageRules($site, $page->id);
    }
}
