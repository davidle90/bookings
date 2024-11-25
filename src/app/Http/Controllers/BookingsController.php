<?php namespace Davidle90\Bookings\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Davidle90\Bookings\app\Models\Bookable;
use Davidle90\Bookings\app\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use DateTime;
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
        $input = [
            'booking_id' => $request->input('booking_id'),
            'bookable_id' => $request->input('bookable_id'),
            'notes' => $request->input('notes'),
            'date' => $request->input('date')
        ];

        try {

            DB::beginTransaction();

            $bookable = Bookable::find($input['bookable_id']);

            $date_array = explode('_', $input['date']);

            $start_date_string = $date_array[0].' '.$date_array[1].':00';
            $end_date_string = $date_array[0].' '.$date_array[2].':00';
            $format = 'Y-m-d H:i:s';

            $start_time = DateTime::createFromFormat($format, $start_date_string);
            $end_time = DateTime::createFromFormat($format, $end_date_string);
        
            $booking = Booking::create([
                'resource_id' => $bookable->id,
                'resource_type' => get_class($bookable),
                'user_id' => null,
                'notes' => $input['notes'],
                'start_time' => $start_time,
                'end_time' => $end_time,
                'status' => 'confirmed',
            ]);

            DB::commit();

            $response = [
                'status' => 1,
                'redirect' => route('admin.bookings.index'),
                'message' => 'Booking has been saved.'
            ];

            $request->session()->put('action_message', $response['message']);

        } catch (\Exception $e) {
            DB::rollback();

            $response = [
                'status' => 0,
                'message' => 'Failed to save booking.'
            ];
        }

        return response()->json($response);
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

    public function get_time_slots(Request $request)
    {
        $bookable_id = $request->input('bookable_id');
        $date = $request->input('bookable_date');
        $day_of_week = (new DateTime($date))->format('l');

        $availability = BookableAvailability::where('bookable_id', $bookable_id)
            ->where('day_of_week', strtolower($day_of_week))
            ->first();

        if (!$availability) {
            $response = [
                'status' => 0,
                'message' => 'No available timeslot found.'
            ];

            return response()->json($response);
        }

        $time_slots = $availability->generateTimeSlots();

        $response = [
            'status' => 1,
            'html' => view('bookings::partials.booking.time_slot_select', [
                'time_slots' => $time_slots,
                'date' => $date
            ])->render(),
        ];

        return response()->json($response);
    }
}