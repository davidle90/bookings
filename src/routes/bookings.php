<?php

use Davidle90\Bookings\app\Http\Controllers\BookingsController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth', 'admin']], function () {

    Route::get('/admin/bookings', [BookingsController::class, 'index'])->name('admin.bookings.index');
    Route::get('/admin/bookings/create', [BookingsController::class, 'create'])->name('admin.bookings.create');
    Route::get('/admin/bookings/{id}', [BookingsController::class, 'edit'])->name('admin.bookings.edit');
    Route::post('/admin/bookings/store', [BookingsController::class, 'store'])->name('admin.bookings.store');
    Route::post('/admin/bookings/delete', [BookingsController::class, 'delete'])->name('admin.bookings.delete');

    Route::get('/admin/bookings/test', [BookingsController::class, 'test'])->name('admin.bookings.test');

});