<?php

namespace Davidle90\Bookings\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function generateTimeSlots($date)
    {

        $slots = [];
        $unavailable_slots = [];

        $start = Carbon::create($this->start_datetime);
        $end = Carbon::create($this->end_datetime);

        $bookable = $this->bookable;
        $bookable_type = get_class($bookable);

        $bookings = Booking::where('resource_id', $bookable->id)->where('resource_type', $bookable_type)->whereNot('status', 'canceled')->get();

        foreach($bookings as $booking){
            $booking_start_datetime = Carbon::create($booking->start_datetime);
            $booking_end_datetime = Carbon::create($booking->end_datetime);

            if($booking_start_datetime->format('Y-m-d') == $date->format('Y-m-d')){
                $start_hour = $booking_start_datetime->format('H:i');
                $end_hour = $booking_end_datetime->format('H:i');

                $unavailable_slots[] = [
                    'start' => $start_hour,
                    'end' => $end_hour
                ];
            }
        }

        $exceptionExists = BookingException::where('bookable_id', $bookable->id)
            ->where('type', 'block')
            ->where('start_datetime', '<', $start)
            ->where('end_datetime', '>', $end)
            ->exists();
        
        $slot_duration = $bookable->availabilities->first()->slot_duration;

        while ($start->lt($end)) {
            $slotStart = $start->copy();
            $slotEnd = $start->addMinutes($slot_duration);

            if ($slotEnd->lte($end)) {

                $new_slot = [
                    'start' => $slotStart->format('H:i'),
                    'end' => $slotEnd->format('H:i'),
                ];

                if(in_array($new_slot, $unavailable_slots) || $exceptionExists){
                    continue;
                }

                $slots[] = $new_slot;
            }
        }

        return $slots;
    }
}
