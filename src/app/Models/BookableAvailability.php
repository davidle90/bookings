<?php

namespace Davidle90\Bookings\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BookableAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'bookable_id',
        'booking_type',
        'day_of_week',
        'start_time',
        'end_time',
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

    public function generateTimeSlots($date)
    {

        $slots = [];
        $unavailable_slots = [];

        if (!Carbon::hasFormat($this->start_time, 'H:i') || !Carbon::hasFormat($this->end_time, 'H:i')) {
            throw new \Exception('Invalid start or end time format');
        }

        $start = Carbon::create($date->format('Y-m-d').' '.$this->start_time);
        $end = Carbon::create($date->format('Y-m-d').' '.$this->end_time);
        $bookable_id = $this->bookable_id;
        $bookable_type = get_class($this->bookable);

        $bookings = Booking::where('resource_id', $bookable_id)->where('resource_type', $bookable_type)->whereNot('status', 'canceled')->get();

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

        // Check if date has exception blocked (recurring none)
        $exceptionExists = BookingException::where('bookable_id', $bookable_id)
            ->where('recurring_type', 'none')
            ->where('type', 'block')
            ->where('start_datetime', '<', $start)
            ->where('end_datetime', '>', $end)
            ->exists();

        // Find available exceptions to add to timeslots (recurring none)
        $available_exception = BookingException::where('bookable_id', $bookable_id)
            ->where('recurring_type', 'none')
            ->where('type', 'available')
            ->where('start_datetime', '>', $date)
            ->where('end_datetime', '<', $end)
            ->first();

        // TODO : recurring type daily and weekly

        // Add timeslots
        while ($start->lt($end)) {
            $slotStart = $start->copy();
            $slotEnd = $start->addMinutes($this->slot_duration);

            if ($slotEnd->lte($end)) {

                $new_slot = [
                    'start' => $slotStart->format('H:i'),
                    'end' => $slotEnd->format('H:i'),
                ];

                if(in_array($new_slot, $unavailable_slots) || $exceptionExists || ($slotStart <= now())){
                    continue;
                }

                $slots[] = $new_slot;
            }

            if($available_exception){
                $exceptionSlotStart = $available_exception->start_datetime->copy();
                $exceptionSlotEnd = $available_exception->start_datetime->addMinutes($this->slot_duration);

                if ($exceptionSlotEnd->lte($available_exception->end_datetime)) {

                    $new_slot = [
                        'start' => $exceptionSlotStart->format('H:i'),
                        'end' => $exceptionSlotEnd->format('H:i'),
                    ];

                    if(in_array($new_slot, $unavailable_slots) || $exceptionExists || ($exceptionSlotStart <= now())){
                        continue;
                    }

                    $slots[] = $new_slot;
                }
            }
        }

        // Get daily exception time slots;
        $dailyExceptions = BookingException::where('bookable_id', $bookable_id)
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
