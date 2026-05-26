<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlockPage extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'site_page_id',
        'content',
        'sort_order',
    ];

    /**
     * @return BelongsTo<SitePage, $this>
     */
    public function sitePage(): BelongsTo
    {
        return $this->belongsTo(SitePage::class);
    }
}
