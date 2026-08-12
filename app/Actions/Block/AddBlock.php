<?php

namespace App\Actions\Block;

use App\Enums\TemplateContext;
use App\Models\Block;
use App\Models\Layout;
use App\Models\SitePage;
use App\Models\Template;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AddBlock
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function handle(SitePage|Layout $parent, array $input): Block
    {
        $validated = Validator::make($input, [
            'template_id' => ['required', 'integer', Rule::exists('templates', 'id')],
        ])->validate();

        $template = Template::findOrFail($validated['template_id']);

        $expectedContext = $parent instanceof SitePage
            ? TemplateContext::Page
            : TemplateContext::Layout;

        if ($template->context !== $expectedContext) {
            throw ValidationException::withMessages([
                'template_id' => ['This template cannot be added here.'],
            ]);
        }

        return DB::transaction(function () use ($parent, $template): Block {
            return $parent->blocks()->create([
                'template_id' => $template->id,
                'content' => $template->default_content,
                'order' => (int) $parent->blocks()->max('order') + 1,
            ]);
        });
    }
}
