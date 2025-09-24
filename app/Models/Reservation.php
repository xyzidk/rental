<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $table = 'reservation';
    protected $fillable = [
        'car_id',
        'name',
        'email',
        'address',
        'phone',
        'date_from',
        'date_to',
        'days',
        'total_price',
    ];
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
