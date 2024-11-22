<?php namespace Davidle90\Bookings\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Davidle90\Bookings\app\Models\Bookable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookablesController extends Controller
{
    public function index()
    {
        $bookables = Bookable::get();

        return view('bookings::pages.admin.bookables.index', [
            'bookables' => $bookables
        ]);
    }

    public function create()
    {
        return view('bookings::pages.admin.bookables.edit');
    }

    public function edit($id)
    {
        $bookable = Bookable::find($id);

        return view('bookings::pages.admin.bookables.edit', [
            'bookable' => $bookable
        ]);
    }

    public function store(Request $request)
    {
        $input = [
            'id' => $request->input('id'),
            'type' => $request->input('type'),
            'name' => $request->input('name'),
            'attributes' => $request->input('attributes', [])
        ];

        $attributes_test = [
            'capacity' => 10,
            'location' => 'First Floor',
        ];

        try {

            DB::beginTransaction();

            $bookable = Bookable::firstOrNew(['id' => $input['id']]);

            $bookable->type = $input['type'];
            $bookable->name = $input['name'];
            $bookable->attributes = $attributes_test;
            $bookable->save();

            DB::commit();

            $response = [
                'status' => 1,
                'redirect' => route('admin.bookings.bookables.edit', ['id' => $bookable->id]),
                'message' => 'Bookable has been created.'
            ];

            $request->session()->put('action_message', $response['message']);

        } catch (\Exception $e) {
            DB::rollback();

            $response = [
                'status' => 0,
                'message' => 'Failed to create bookable.'
            ];
        }

        return response()->json($response);
    }

    public function delete(Request $request)
    {
        $id = $request->get('delete_id');
        $bookable = Bookable::find($id);

        $bookable->delete();

        $response = [
            'status' => 1,
            'message' => 'Bookable has been deleted',
            'redirect' => route('admin.bookings.bookables.index')
        ];

        return response()->json($response);
    }
}