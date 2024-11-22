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
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function resource()
    {
        return $this->morphTo();
    }
}
