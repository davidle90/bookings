<?php

namespace Davidle90\Bookings\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_id',
        'resource_type',
        'user_id',
        'status',
        'start_datetime',
        'end_datetime',
        'notes',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'start_datetime',
        'end_datetime'
    ];

    public function resource()
    {
        return $this->morphTo();
    }

    public function bookable()
    {
        return $this->morphTo('resource');
    }
}
