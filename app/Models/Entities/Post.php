<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id', 'title', 'content',
    ];

    public function applicant()
    {
        return $this->belongsTo('App\Entities\Applicant');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($post) {
            Log::info("Post created: " . $post->id);
        });

        static::updated(function ($post) {

            Log::info("Post updated: " . $post->id);
        });


        static::deleting(function ($post){
            Log::info("Post deleted: " . $post->id);
        });

        // static::deleting(function ($post) {
        //     $post->comments()->delete();
        // });
    }

    public function user()
    {
        return $this->belongsTo('App\Entities\User');
    }
    
    use HasFactory;
}

