<?php namespace Davidle90\Bookings\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Davidle90\Bookings\app\Models\Bookable;
use Davidle90\Bookings\app\Models\Booking;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    public function index()
    {
        $bookable = Bookable::find(1);
        $bookings = $bookable ? $bookable->bookings : null;

        return view('bookings::pages.admin.bookings.index', [
            'bookings' => $bookings
        ]);
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
        $room = Bookable::where('type', 'room')->first();

        $booking = Booking::create([
            'resource_id' => $room->id,
            'resource_type' => Bookable::class,
            'user_id' => 1,
            'start_time' => now(),
            'end_time' => now()->addHours(2),
            'status' => 'confirmed',
            'notes' => 'Projector required',
        ]);

        //test toaster

        $response = [
            'status' => 1,
            'message' => 'Booking has been created'
        ];

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
}