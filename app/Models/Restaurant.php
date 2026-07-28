<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $primaryKey = 'restaurant_id';
    
    protected $fillable = [
        'admin_id',
        'restaurant_name',
        'contact_number',
        'address',
        'rating',
        'price_level',
        'latitude',
        'longitude',
    ];
}
