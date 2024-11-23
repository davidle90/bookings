<?php namespace Davidle90\Bookings\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Davidle90\Bookings\app\Models\Bookable;
use Davidle90\Bookings\app\Models\Bookableexception;
use Davidle90\Bookings\app\Models\BookingException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExceptionsController extends Controller
{
    public function index()
    {
        $exceptions = BookingException::get();

        return view('bookings::pages.admin.exceptions.index', [
            'exceptions' => $exceptions
        ]);
    }

    public function create()
    {
        $bookables = Bookable::get();

        return view('bookings::pages.admin.exceptions.edit', [
            'bookables' => $bookables
        ]);
    }

    public function edit($id)
    {
        $exception = BookingException::find($id);
        $bookables = Bookable::get();

        return view('bookings::pages.admin.exceptions.edit', [
            'exception' => $exception,
            'bookables' => $bookables
        ]);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'bookable_id' => 'required|exists:bookables,id',
            'exception_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'type' => 'required|in:block,available',
            'notes' => 'nullable|string|max:255',
        ]);

        try {

            DB::beginTransaction();

            $exception = BookingException::create($validated);

            DB::commit();

            $response = [
                'status' => 1,
                'redirect' => route('admin.bookings.exceptions.edit', ['id' => $exception->id]),
                'message' => 'Exception has been saved.'
            ];

            $request->session()->put('action_message', $response['message']);

        } catch(\Exception) {

            DB::rollback();

            $response = [
                'status' => 0,
                'message' => 'Failed to create Exception.'
            ];
        }
    
        return response()->json($response);
    }

    public function delete(Request $request)
    {
        $id = $request->get('delete_id');
        $exception = BookingException::find($id);

        $exception->delete();

        $response = [
            'status' => 1,
            'message' => 'Exception has been deleted',
            'redirect' => route('admin.bookings.exceptions.index')
        ];

        return response()->json($response);
    }
}