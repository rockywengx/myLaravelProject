<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
    ];

    public function posts()
    {
        return $this->hasMany('App\Models\Entities\Post');
    }

    use HasFactory;
}
