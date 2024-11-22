<?php

use Davidle90\Bookings\app\Http\Controllers\BookingsController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['web', 'auth', 'admin']], function () {

    Route::get('/admin/bookings', [BookingsController::class, 'index'])->name('admin.bookings.index');

});