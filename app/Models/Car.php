<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;
    protected $table = 'car';

    protected $fillable = [
        'type',
        'plate_number',
        'rental_price',
        'image_path',
        'status',
    ];
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    
}
