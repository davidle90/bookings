<?php

namespace Davidle90\Bookings\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingException extends Model
{
    use HasFactory;

    protected $fillable = [
        'bookable_id', 
        'exception_date', 
        'start_time', 
        'end_time', 
        'type', 
        'notes'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function bookable()
    {
        return $this->belongsTo(Bookable::class);
    }

    public function scopeForDate($query, $bookableId, $date)
    {
        return $query->where('bookable_id', $bookableId)
                     ->where('exception_date', $date);
    }
}
