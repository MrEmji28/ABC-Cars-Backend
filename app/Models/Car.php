<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'brand',
        'model',
        'year',
        'price',
        'rental_price_per_day',
        'type',
        'status',
        'image_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
