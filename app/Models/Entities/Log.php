<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    protected $fillable = [
        'Post_id',
        'title',
        'content',
        'ation',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo('App\Models\Entities\Post');
    }

    use HasFactory;
}
