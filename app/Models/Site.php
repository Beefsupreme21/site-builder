<?php

namespace App\Models;

use Database\Factories\SiteFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    /** @use HasFactory<SiteFactory> */
    use HasFactory;

    /**
     * Public site preview Blade views live at resources/views/sites/templates/{key}/pages/
     */
    public const TEMPLATES = ['default', 'alternate'];

    /**
     * @var list<array{slug: string, title: string, content: string, sort_order: int}>
     */
    public const DEFAULT_PAGES = [
        [
            'slug' => 'home',
            'title' => '', // filled from company_name on create
            'content' => 'Building trusted relationships and delivering excellence for every client we serve.',
            'sort_order' => 0,
        ],
        [
            'slug' => 'about',
            'title' => 'About Us',
            'content' => 'We are a dedicated team committed to quality, integrity, and lasting partnerships with the communities we serve.',
            'sort_order' => 1,
        ],
        [
            'slug' => 'contact',
            'title' => 'Contact Us',
            'content' => 'We would love to hear from you. Send us a message and we will respond as soon as we can.',
            'sort_order' => 2,
        ],
    ];

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

    protected static function booted(): void
    {
        static::created(function (Site $site): void {
            $site->createDefaultPages();
        });
    }

    /**
     * Resolve which preview view key to use (invalid values fall back to default).
     */
    public function previewTemplateKey(): string
    {
        return in_array($this->template, self::TEMPLATES, true)
            ? $this->template
            : 'default';
    }

    public function previewUrl(?SitePage $page = null): string
    {
        $page ??= $this->homePage();

        if ($page === null) {
            return route('sites.preview.home', $this);
        }

        return route('sites.preview', [
            'site' => $this,
            'page' => $page,
        ]);
    }

    /**
     * @return HasMany<SitePage, $this>
     */
    public function pages(): HasMany
    {
        return $this->hasMany(SitePage::class)->orderBy('sort_order');
    }

    public function homePage(): ?SitePage
    {
        if ($this->relationLoaded('pages')) {
            return $this->pages->firstWhere('slug', 'home')
                ?? $this->pages->sortBy('sort_order')->first();
        }

        return $this->pages()->where('slug', 'home')->first()
            ?? $this->pages()->orderBy('sort_order')->first();
    }

    public function contactPage(): ?SitePage
    {
        if ($this->relationLoaded('pages')) {
            return $this->pages->firstWhere('slug', 'contact');
        }

        return $this->pages()->where('slug', 'contact')->first();
    }

    public function createDefaultPages(): void
    {
        foreach (self::DEFAULT_PAGES as $defaults) {
            $this->pages()->firstOrCreate(
                ['slug' => $defaults['slug']],
                [
                    'title' => $defaults['slug'] === 'home'
                        ? $this->company_name
                        : $defaults['title'],
                    'content' => $defaults['content'],
                    'sort_order' => $defaults['sort_order'],
                ],
            );
        }
    }

    /**
     * @return HasMany<Lead, $this>
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
