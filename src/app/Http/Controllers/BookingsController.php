<?php namespace Davidle90\Bookings\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Davidle90\Bookings\app\Models\Bookable;
use Davidle90\Bookings\app\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Davidle90\Bookings\app\Helpers\bookings_helper;
use Davidle90\Bookings\app\Models\BookableAvailability;

class BookingsController extends Controller
{
    public function index(Request $request)
    {
        // Get the current month and year (or from the request)
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $bookable_id = 4;

        $calendar_data = bookings_helper::get_calendar_data($bookable_id, $month, $year);

        $route_name = 'admin.bookings.index';

        return view('bookings::pages.admin.bookings.index', [
            'bookable' => $calendar_data['bookable'],
            'bookings' => $calendar_data['bookings'],
            'month' => $calendar_data['month'],
            'year' => $calendar_data['year'],
            'startOfMonth' => $calendar_data['startOfMonth'],
            'endOfMonth' => $calendar_data['endOfMonth'],
            'route_name' => $route_name
        ]);
    }

    public function create()
    {
        $bookables = Bookable::get();

        $month = now()->month;
        $year = now()->year;

        $calendar_data = bookings_helper::get_calendar_data(null, $month, $year);

        return view('bookings::pages.admin.bookings.edit', [
            'bookables' => $bookables,
            'selected_bookable' => $calendar_data['bookable'],
            'bookings' => $calendar_data['bookings'],
            'month' => $calendar_data['month'],
            'year' => $calendar_data['year'],
            'startOfMonth' => $calendar_data['startOfMonth'],
            'endOfMonth' => $calendar_data['endOfMonth'],
        ]);
    }

    public function edit($id)
    {
        $booking = Booking::find($id);

        return view('bookings::pages.admin.bookings.edit', [
            'booking' => $booking
        ]);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'resource_id' => 'required|integer',
            'resource_type' => 'required|string',
            'start_time' => 'required|date_format:Y-m-d H:i',
            'end_time' => 'required|date_format:Y-m-d H:i|after:start_time',
        ]);
    
        Booking::create([
            'resource_id' => $validated['resource_id'],
            'resource_type' => $validated['resource_type'],
            'user_id' => auth()->id(),
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => 'confirmed',
        ]);
    
        return redirect()->back()->with('message', 'Booking confirmed.');
    }

    public function delete(Request $request)
    {
        $response = [
            'status' => 1,
            'message' => 'Booking has been deleted'
        ];

        return response()->json($response);
    }

    public function get_bookings(Request $request)
    {
        $bookable_id = $request->bookable_id;
        $month = $request->month;
        $year = $request->year;

        $calendar_data = bookings_helper::get_calendar_data($bookable_id, $month, $year);

        $html = view('bookings::partials.booking.grid_data', [
            'selected_bookable' => $calendar_data['bookable'],
            'bookings' => $calendar_data['bookings'],
            'month' => $calendar_data['month'],
            'year' => $calendar_data['year'],
            'startOfMonth' => $calendar_data['startOfMonth'],
            'endOfMonth' => $calendar_data['endOfMonth'],
        ])->render();

        $response = [
            'grid_data' => $html
        ];

        return response()->json($response);
    }
}