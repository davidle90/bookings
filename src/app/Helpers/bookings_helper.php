<?php namespace Davidle90\Bookings\app\Helpers;

use Carbon\Carbon;
use DateTime;
use Davidle90\Bookings\app\Models\Bookable;
use Davidle90\Bookings\app\Models\BookableAvailability;
use Davidle90\Bookings\app\Models\Booking;
use Davidle90\Bookings\app\Models\BookingException;

class bookings_helper
{
    public static function get_calendar_data($bookable_id, $month, $year)
    {
        $startOfMonth = Carbon::create($year, $month)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $bookings = Booking::where('bookable_id', $bookable_id)
            ->whereBetween('start_time', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy(function ($booking) {
                return Carbon::parse($booking->start_time)->format('Y-m-d');
            });

        $bookable = Bookable::find($bookable_id);

        $data = [
            'bookable' => $bookable,
            'bookings' => $bookings,
            'month' => $month,
            'year' => $year,
            'startOfMonth' => $startOfMonth,
            'endOfMonth' => $endOfMonth,
        ];

        return $data;
    }

    public static function is_booking_available($bookable_type, $bookable_id, $start_datetime, $end_datetime)
    {
        $bookingExists = Booking::where('resource_type', $bookable_type)
            ->where('resource_id', $bookable_id)
            ->whereNot('status', 'canceled')
            ->whereBetween('start_datetime', [$start_datetime, $end_datetime])
            ->whereBetween('end_datetime', [$start_datetime, $end_datetime])
            ->exists();
        
        if($bookingExists){
            return false;
        }

        $exceptionExists = BookingException::where('bookable_id', $bookable_id)
            ->where('type', 'block')
            ->whereBetween('start_datetime', [$start_datetime, $end_datetime])
            ->whereBetween('end_datetime', [$start_datetime, $end_datetime])
            ->exists();
        
        if($exceptionExists)
        {
            return false;
        }

        return true;
    }
}
