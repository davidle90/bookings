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
use Davidle90\Bookings\app\Models\BookingException;

class BookingsController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::get();

        return view('bookings::pages.admin.bookings.index', [
            'bookings' => $bookings
        ]);
    }

    public function create()
    {
        $bookables = Bookable::where('is_active', 1)->get();

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
            $start_datetime = Carbon::create($date_array[0].' '.$date_array[1]);
            $end_datetime = Carbon::create($date_array[0].' '.$date_array[2]);

            $bookable_type = get_class($bookable);

            $booking_available = bookings_helper::is_booking_available($bookable_type, $bookable->id, $start_datetime, $end_datetime);

            if(!$booking_available){
                $response = [
                    'status' => 0,
                    'message' => 'Bokningen är inte längre tillänglig. Vänligen välj en ny tid.'
                ];

                $request->session()->put('action_message', $response['message']);

                return response()->json($response);
            }
        
            Booking::create([
                'resource_id' => $bookable->id,
                'resource_type' => $bookable_type,
                'user_id' => null,
                'notes' => $input['notes'],
                'start_datetime' => $start_datetime,
                'end_datetime' => $end_datetime,
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
                'message' => 'Failed to save booking: '.$e->getMessage()
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
        $formatted_date = Carbon::create($date);
        $day_of_week = strtolower($formatted_date->format('l'));

        $availability = BookableAvailability::where('bookable_id', $bookable_id)->where('day_of_week', $day_of_week)->first();

        $time_slots = $availability->generateTimeSlots($formatted_date);
    
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