<?php

namespace Davidle90\Bookings\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookableAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'bookable_id',
        'day_of_week',
        'start_time',
        'slot_duration'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function bookable()
    {
        return $this->belongsTo(Bookable::class);
    }

    public function generateTimeSlots()
    {
        $slots = [];
        $start = \Carbon\Carbon::createFromTimeString($this->start_time);
        $end = \Carbon\Carbon::createFromTimeString($this->end_time);

        while ($start->lt($end)) {
            $slotStart = $start->copy();
            $slotEnd = $start->addMinutes($this->slot_duration);

            if ($slotEnd->lte($end)) {
                $slots[] = [
                    'start' => $slotStart->format('H:i'),
                    'end' => $slotEnd->format('H:i'),
                ];
            }
        }

        return $slots;
    }
}
