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
     * @var list<string>
     */
    protected $fillable = [
        'slug',
        'company_name',
        'phone',
        'email',
        'logo',
        'primary_color',
        'secondary_color',
    ];

    protected static function booted(): void
    {
        static::created(function (Site $site): void {
            $site->createDefaultHomePage();
        });
    }

    public function previewUrl(?SitePage $page = null): string
    {
        $page ??= $this->homePage();

        if ($page === null) {
            return route('preview.index', $this);
        }

        return route('preview.show', $page);
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

    public function createDefaultHomePage(): void
    {
        $this->pages()->firstOrCreate(
            ['slug' => 'home'],
            [
                'title' => $this->company_name,
                'sort_order' => 0,
            ],
        );
    }
}
