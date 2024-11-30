<?php

use Davidle90\Bookings\app\Http\Controllers\ApiController;
use Davidle90\Bookings\app\Http\Controllers\AvailabilitiesController;
use Davidle90\Bookings\app\Http\Controllers\BookingsController;
use Davidle90\Bookings\app\Http\Controllers\BookablesController;
use Davidle90\Bookings\app\Http\Controllers\ExceptionsController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth', 'admin']], function () {

    Route::get('/admin/bookings', [BookingsController::class, 'index'])->name('admin.bookings.index');
    Route::get('/admin/bookings/create', [BookingsController::class, 'create'])->name('admin.bookings.create');
    Route::get('/admin/bookings/edit/{id}', [BookingsController::class, 'edit'])->name('admin.bookings.edit');
    Route::post('/admin/bookings/store', [BookingsController::class, 'store'])->name('admin.bookings.store');
    Route::post('/admin/bookings/delete', [BookingsController::class, 'delete'])->name('admin.bookings.delete');

    Route::get('/admin/bookings/bookables', [BookablesController::class, 'index'])->name('admin.bookings.bookables.index');
    Route::get('/admin/bookings/bookables/create', [BookablesController::class, 'create'])->name('admin.bookings.bookables.create');
    Route::get('/admin/bookings/bookables/edit/{id}', [BookablesController::class, 'edit'])->name('admin.bookings.bookables.edit');
    Route::post('/admin/bookings/bookables/store', [BookablesController::class, 'store'])->name('admin.bookings.bookables.store');
    Route::post('/admin/bookings/bookables/delete', [BookablesController::class, 'delete'])->name('admin.bookings.bookables.delete');

    Route::get('/admin/bookings/availabilities', [AvailabilitiesController::class, 'index'])->name('admin.bookings.availabilities.index');
    Route::get('/admin/bookings/availabilities/edit/{bookable_id}', [AvailabilitiesController::class, 'edit'])->name('admin.bookings.availabilities.edit');
    Route::post('/admin/bookings/availabilities/store', [AvailabilitiesController::class, 'store'])->name('admin.bookings.availabilities.store');
    Route::post('/admin/bookings/availabilities/delete', [AvailabilitiesController::class, 'delete'])->name('admin.bookings.availabilities.delete');

    Route::get('/admin/bookings/exceptions', [ExceptionsController::class, 'index'])->name('admin.bookings.exceptions.index');
    Route::get('/admin/bookings/exceptions/create', [ExceptionsController::class, 'create'])->name('admin.bookings.exceptions.create');
    Route::get('/admin/bookings/exceptions/edit/{id}', [ExceptionsController::class, 'edit'])->name('admin.bookings.exceptions.edit');
    Route::post('/admin/bookings/exceptions/store', [ExceptionsController::class, 'store'])->name('admin.bookings.exceptions.store');
    Route::post('/admin/bookings/exceptions/delete', [ExceptionsController::class, 'delete'])->name('admin.bookings.exceptions.delete');

    Route::get('/admin/bookings/get_bookings', [BookingsController::class, 'get_bookings'])->name('admin.bookings.get_bookings');
    Route::get('/admin/bookings/get_time_slots', [BookingsController::class, 'get_time_slots'])->name('admin.bookings.get_time_slots');
});