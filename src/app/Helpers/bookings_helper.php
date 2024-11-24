<?php namespace Davidle90\Bookings\app\Helpers;

use Carbon\Carbon;
use Davidle90\Bookings\app\Models\Bookable;
use Davidle90\Bookings\app\Models\BookableAvailability;
use Davidle90\Bookings\app\Models\Booking;

class bookings_helper
{
    public static function get_calendar_data($bookable_id, $month, $year)
    {
        // Get the first and last days of the month
        $startOfMonth = Carbon::create($year, $month)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Get bookings for the month
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

    public static function get_time_slots($bookable_id, $day_of_Week)
    {
        $availability = BookableAvailability::where('bookable_id', $bookable_id)
            ->where('day_of_week', $day_of_Week)
            ->first();

        if (!$availability) {
            return response()->json(['error' => 'No availability found'], 404);
        }

        $time_slots = $availability->generateTimeSlots();

        return $time_slots;
    }
}
