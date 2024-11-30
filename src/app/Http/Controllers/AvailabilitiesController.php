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
        $bookables = Bookable::paginate(25);

        return view('bookings::pages.admin.availabilities.index', [
            'bookables' => $bookables
        ]);
    }

    public function edit($bookable_id,)
    {
        $bookable = Bookable::find($bookable_id);

        $days = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];

        return view('bookings::pages.admin.availabilities.edit', [
            'bookable' => $bookable,
            'days' => $days
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->all();

        try {
            DB::beginTransaction();

            foreach ($input['days_of_week'] as $day => $data) {

                if (empty($data['enabled'])) {
                    $delete_availability = BookableAvailability::where('bookable_id', $input['bookable_id'])->where('day_of_week', $day)->first();
                    if($delete_availability){
                        $delete_availability->delete();
                    }
                    continue;
                }

                $availability = BookableAvailability::firstOrNew([
                    'bookable_id' => $input['bookable_id'],
                    'day_of_week' => $day,
                ]);

                $availability->start_time = isset($data['start_time']) ? $data['start_time'] : '00:00';
                $availability->end_time = isset($data['end_time']) ? $data['end_time'] : '00:00';

                if($availability->start_time > $availability->end_time){
                    $response = [
                        'status' => 0,
                        'message' => 'End time must be greater than start time.',
                    ];

                    return response()->json($response);
                }

                $availability->slot_duration = $input['slot_duration'] ?? 60;

                $availability->save();
            }

            DB::commit();

            $response = [
                'status' => 1,
                'redirect' => route('admin.bookings.availabilities.index'),
                'message' => 'Availability has been saved successfully.',
            ];

            $request->session()->put('action_message', $response['message']);

        } catch (\Exception $e) {
            DB::rollback();

            $response = [
                'status' => 0,
                'message' => $e->getMessage(),
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