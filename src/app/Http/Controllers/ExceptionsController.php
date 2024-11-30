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
        $exceptions = BookingException::paginate(25);

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
        $input = [
            'id' => $request->input('id'),
            'label' => $request->input('label'),
            'bookable_id' => $request->input('bookable_id'),
            'start_datetime' => $request->input('start_datetime'),
            'end_datetime' => $request->input('end_datetime'),
            'type' => $request->input('type'),
            'notes' => $request->input('notes'),
            'is_global' => $request->input('is_global', 0)
        ];
        
        try {
            DB::beginTransaction();

            $exception = BookingException::firstOrNew(['id' => $input['id']]);

            $exception->label = $input['label'];
            $exception->bookable_id = $input['bookable_id'] ?? null;
            $exception->start_datetime = $input['start_datetime'] ?? null;
            $exception->end_datetime = $input['end_datetime'] ?? null;
            $exception->type = $input['type'];
            $exception->notes = $input['notes'] ?? null;
            $exception->is_global = $input['is_global'];

            $exception->save();

            DB::commit();

            $response = [
                'status' => 1,
                'redirect' => route('admin.bookings.exceptions.edit', ['id' => $exception->id]),
                'message' => 'Exception has been saved successfully.'
            ];

            $request->session()->put('action_message', $response['message']);

        } catch (\Exception $e) {
            DB::rollback();

            $response = [
                'status' => 0,
                'message' => 'Failed to create Exception. ' . $e->getMessage()
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