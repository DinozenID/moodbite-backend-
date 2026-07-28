<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'foods';
    protected $primaryKey = 'food_id';
    
    protected $fillable = [
        'restaurant_id',
        'food_name',
        'food_category',
        'food_description',
        'price',
        'rating',
    ];
}
