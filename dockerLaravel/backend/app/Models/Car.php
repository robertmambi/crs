<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'owner_id',
        'brand',
        'model',
        'year',
        'plate_number',
        'vin_number',
        'color',
        'transmission',
        'fuel_type',
        'seats',
        'price_per_day',
        'status',
        'mileage',
        'insurance_expiry_date',
        'inspection_expiry_date',
        'road_tax_expiry_date',
        'last_oil_change_date',
        'image_url',
        'description',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}