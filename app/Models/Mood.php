<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mood extends Model
{
    protected $primaryKey = 'mood_id';
    
    protected $fillable = [
        'mood_name',
        'description',
    ];
}
