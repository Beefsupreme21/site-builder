<?php

namespace App\Models;

use Database\Factories\SitePageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['site_id', 'slug', 'title', 'sort_order'])]
class SitePage extends Model
{
    /** @use HasFactory<SitePageFactory> */
    use HasFactory;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return BelongsTo<Site, $this>
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * @return HasMany<BlockPage, $this>
     */
    public function blockPages(): HasMany
    {
        return $this->hasMany(BlockPage::class)->orderBy('sort_order');
    }

    /**
     * @return HasMany<BlockPage, $this>
     */
    public function blocks(): HasMany
    {
        return $this->blockPages();
    }

    public function previewUrl(): string
    {
        return route('preview.show', $this);
    }
}
