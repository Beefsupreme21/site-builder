<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['site_page_id', 'content', 'sort_order'])]
class BlockPage extends Model
{
    /**
     * @return BelongsTo<SitePage, $this>
     */
    public function sitePage(): BelongsTo
    {
        return $this->belongsTo(SitePage::class);
    }
}
