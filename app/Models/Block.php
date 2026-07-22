<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'category',
        'type',
        'default_content',
    ];
}
