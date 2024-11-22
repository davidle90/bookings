<?php

use Davidle90\Bookings\app\Http\Controllers\BookingsController;
use Davidle90\Bookings\app\Http\Controllers\BookablesController;

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
});