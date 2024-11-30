<?php

namespace Davidle90\Bookings\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingException extends Model
{
    use HasFactory;

    protected $fillable = [
        'bookable_id', 
        'label',
        'start_datetime',
        'end_datetime',
        'type', 
        'notes',
        'is_global'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function bookable()
    {
        return $this->belongsTo(Bookable::class);
    }
}
