<?php namespace Davidle90\Bookings\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Davidle90\Bookings\app\Models\Bookable;
use Davidle90\Bookings\app\Models\BookableAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AvailabilitiesController extends Controller
{
    public function index()
    {
        $availabilities = BookableAvailability::get();

        return view('bookings::pages.admin.availabilities.index', [
            'availabilities' => $availabilities
        ]);
    }

    public function create()
    {
        $bookables = Bookable::get();

        return view('bookings::pages.admin.availabilities.edit', [
            'bookables' => $bookables
        ]);
    }

    public function edit($id)
    {
        $availability = BookableAvailability::find($id);
        $bookables = Bookable::get();

        return view('bookings::pages.admin.availabilities.edit', [
            'availability' => $availability,
            'bookables' => $bookables
        ]);
    }

    public function store(Request $request)
    {

        $input = [
            'bookable_id' => $request->input('bookable'),
            'days_of_week' => $request->input('days_of_week'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'slot_duration' => $request->input('slot_duration')
        ];

        // $validated = $request->validate([
        //     'days' => 'required|array',
        //     'days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
        //     'start_time' => 'required|date_format:H:i',
        //     'end_time' => 'required|date_format:H:i|after:start_time',
        //     'slot_duration' => 'required|integer|min:15', // Minimum 15 minutes
        // ]);

        try {

            DB::beginTransaction();

            foreach($input['days_of_week'] as $day){
                $availability = BookableAvailability::firstOrNew([
                    'bookable_id' => $input['bookable_id'],
                    'day_of_week' => $day
                ]);
    
                $availability->start_time = $input['start_time'];
                $availability->end_time = $input['end_time'];
                $availability->slot_duration = $input['slot_duration'];
    
                $availability->save();
            }

            DB::commit();

            $response = [
                'status' => 1,
                'redirect' => route('admin.bookings.availabilities.edit', ['id' => $availability->id]),
                'message' => 'Availability has been saved.'
            ];

            $request->session()->put('action_message', $response['message']);

        } catch(\Exception) {

            DB::rollback();

            $response = [
                'status' => 0,
                'message' => 'Failed to create availability.'
            ];
        }
    
        return response()->json($response);
    }

    public function delete(Request $request)
    {
        $id = $request->get('delete_id');
        $availability = BookableAvailability::find($id);

        $availability->delete();

        $response = [
            'status' => 1,
            'message' => 'Availability has been deleted',
            'redirect' => route('admin.bookings.availabilities.index')
        ];

        return response()->json($response);
    }
}