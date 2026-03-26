<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidatesSiteInput;
use App\Models\Site;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteRequest extends FormRequest
{
    use ValidatesSiteInput;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('template')) {
            $this->normalizeTemplateInput();
        }
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Site $site */
        $site = $this->route('site');

        return $this->siteRules($site->id);
    }
}
