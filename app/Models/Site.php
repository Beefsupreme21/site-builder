<?php

namespace App\Models;

use Database\Factories\SiteFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['slug', 'company_name', 'phone', 'email', 'logo', 'primary_color', 'secondary_color'])]
class Site extends Model
{
    /** @use HasFactory<SiteFactory> */
    use HasFactory;

    public function previewUrl(?SitePage $page = null): string
    {
        $page ??= $this->homePage();

        if ($page === null) {
            return route('preview.index', $this);
        }

        return route('preview.show', [$this, $page]);
    }

    /**
     * @return HasMany<Layout, $this>
     */
    public function layouts(): HasMany
    {
        return $this->hasMany(Layout::class);
    }

    /**
     * @return HasMany<SitePage, $this>
     */
    public function pages(): HasMany
    {
        return $this->hasMany(SitePage::class)->orderBy('order');
    }

    public function defaultLayout(): ?Layout
    {
        if ($this->relationLoaded('layouts')) {
            return $this->layouts->sortBy('id')->first();
        }

        return $this->layouts()->oldest('id')->first();
    }

    public function homePage(): ?SitePage
    {
        if ($this->relationLoaded('pages')) {
            return $this->pages->firstWhere('slug', 'home')
                ?? $this->pages->sortBy('order')->first();
        }

        return $this->pages()->where('slug', 'home')->first()
            ?? $this->pages()->orderBy('order')->first();
    }

    protected static function booted(): void
    {
        static::deleting(function (Site $site): void {
            $site->pages()->each(fn (SitePage $page) => $page->delete());
            $site->layouts()->each(fn (Layout $layout) => $layout->delete());
        });
    }
}
