<?php

namespace App\Models;

use App\Enums\TemplateContext;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['context', 'name', 'category', 'type', 'default_content'])]
class Template extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'context' => TemplateContext::class,
        ];
    }
}
