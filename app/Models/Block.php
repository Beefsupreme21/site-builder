<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Block extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'type',
        'default_content',
    ];

    /**
     * Human label derived from the type (e.g. "hero_centered" → "Hero Centered").
     */
    public function displayName(): string
    {
        return Str::headline($this->type);
    }
}
