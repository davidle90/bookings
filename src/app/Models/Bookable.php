<?php

namespace Davidle90\Bookings\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookable extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'attributes'
    ];

    protected $casts = [
        'attributes' => 'array', // Cast JSON attributes to an array
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function bookings()
    {
        return $this->morphMany(Booking::class, 'resource');
    }
}
