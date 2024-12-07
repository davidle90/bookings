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
        'recurring_type',
        'start_datetime',
        'end_datetime',
        'start_time',
        'end_time',
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
        $slot_duration = $bookable->availabilities->first()->slot_duration;

        $bookings = Booking::where('resource_id', $bookable->id)->where('resource_type', $bookable_type)->whereNot('status', 'canceled')->get();

        // Find taken time slots
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

        // Check if date has exception block (recurring none)
        $exceptionExists = BookingException::where('bookable_id', $bookable->id)
            ->where('recurring_type', 'none')
            ->where('type', 'block')
            ->where('start_datetime', '<', $start)
            ->where('end_datetime', '>', $end)
            ->exists();
        
        // Generate time slots
        while ($start->lt($end)) {
            $slotStart = $start->copy();
            $slotEnd = $start->addMinutes($slot_duration);

            if ($slotEnd->lte($end)) {

                $new_slot = [
                    'start' => $slotStart->format('H:i'),
                    'end' => $slotEnd->format('H:i'),
                ];

                if(in_array($new_slot, $unavailable_slots) || $exceptionExists || ($slotStart < now())){
                    continue;
                }

                $slots[] = $new_slot;
            }
        }

        // Get daily exception time slots;
        $dailyExceptions = BookingException::where('bookable_id', $bookable->id)
            ->where('recurring_type', 'daily')
            ->where('type', 'block')
            ->get();

        foreach($dailyExceptions as $dailyException){
            $exception_start = $dailyException->start_time;
            $exception_end = $dailyException->end_time;

            foreach($slots as $key => $slot){
                if(($exception_start > $slot['start'] && $exception_start < $slot['end']) ||
                    ($exception_end > $slot['start'] && $exception_end < $slot['end']) ||
                    ($slot['start'] >= $exception_start && $slot['end'] <= $exception_end)) {
                        unset($slots[$key]);
                }
            }
        }

        $slots = array_values($slots);

        return $slots;
    }
}
