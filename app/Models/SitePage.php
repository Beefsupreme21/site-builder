<?php

namespace App\Models;

use Database\Factories\SitePageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SitePage extends Model
{
    /** @use HasFactory<SitePageFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'site_id',
        'slug',
        'title',
        'content',
        'sort_order',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isContactPage(): bool
    {
        return $this->slug === 'contact';
    }

    public function isHomePage(): bool
    {
        return $this->slug === 'home';
    }

    /**
     * @return BelongsTo<Site, $this>
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function previewUrl(): string
    {
        return route('sites.preview', [
            'site' => $this->site,
            'page' => $this,
        ]);
    }
}
