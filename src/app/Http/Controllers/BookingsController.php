<?php namespace Davidle90\Bookings\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    public function index()
    {
        return view('bookings::pages.admin.bookings.index');
    }
}