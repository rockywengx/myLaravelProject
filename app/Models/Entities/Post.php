<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id', 'title', 'content',
    ];

    public function logs(): HasMany
    {
        return $this->hasMany('App\Models\Entities\Log');
    }
    // public function applicant(): BelongsTo
    // {
    //     return $this->belongsTo("App\Models\Entities\Applicant");
    // }

        public function user()
    {
        return $this->BelongsTo('App\Entities\User');
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::created(function ($post) {
    //         Log::info("Post created: " . $post->id);
    //     });

    //     static::updated(function ($post) {

    //         Log::info("Post updated: " . $post->id);
    //     });


    //     static::deleting(function ($post){
    //         Log::info("Post deleted: " . $post->id . ", title: " . $post->title . ", content: " . $post->content);
    //     });

    //     // static::deleting(function ($post) {
    //     //     $post->comments()->delete();
    //     // });
    // }



    use HasFactory;
}

