<?php namespace Davidle90\Bookings\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Davidle90\Bookings\app\Models\Bookable;
use Davidle90\Bookings\app\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingsController extends Controller
{
    public function index(Request $request)
    {

        // Get the current month and year (or from the request)
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // Get the first and last days of the month
        $startOfMonth = Carbon::create($year, $month)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Get bookings for the month
        $bookings = Booking::whereBetween('start_time', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy(function ($booking) {
                return Carbon::parse($booking->start_time)->format('Y-m-d');
            });

        return view('bookings::pages.admin.bookings.calendar', [
            'bookings' => $bookings,
            'month' => $month,
            'year' => $year,
            'startOfMonth' => $startOfMonth,
            'endOfMonth' => $endOfMonth,
        ]);

        // $bookable = Bookable::find(1);
        // $bookings = $bookable ? $bookable->bookings : null;

        // return view('bookings::pages.admin.bookings.index', [
        //     'bookings' => $bookings
        // ]);
    }

    public function create()
    {
        return view('bookings::pages.admin.bookings.edit');
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

        // $room = Bookable::where('type', 'room')->first();

        // $booking = Booking::create([
        //     'resource_id' => $room->id,
        //     'resource_type' => Bookable::class,
        //     'user_id' => 1,
        //     'start_time' => now(),
        //     'end_time' => now()->addHours(2),
        //     'status' => 'confirmed',
        //     'notes' => 'Projector required',
        // ]);

        // $response = [
        //     'status' => 1,
        //     'message' => 'Booking has been created'
        // ];

        // return response()->json($response);
    }

    public function delete(Request $request)
    {
        $response = [
            'status' => 1,
            'message' => 'Booking has been deleted'
        ];

        return response()->json($response);
    }
}