<?php

namespace App\Models;

use Database\Factories\SiteFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    /** @use HasFactory<SiteFactory> */
    use HasFactory;

    /**
     * Public site preview Blade views live at resources/views/sites/templates/{key}.blade.php
     */
    public const TEMPLATES = ['default', 'alternate'];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'slug',
        'template',
        'company_name',
        'phone',
        'email',
        'logo',
    ];

    /**
     * Resolve which preview view key to use (invalid values fall back to default).
     */
    public function previewTemplateKey(): string
    {
        return in_array($this->template, self::TEMPLATES, true)
            ? $this->template
            : 'default';
    }
}
