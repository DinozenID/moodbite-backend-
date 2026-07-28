<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    protected $primaryKey = 'recommendation_id';
    
    protected $fillable = [
        'user_id',
        'food_id',
        'mood_id',
        'budget',
        'recommendation_score',
        'recommended_at',
    ];
}
