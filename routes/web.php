<?php
declare(strict_types=1);

use App\Http\Controllers\Appointments;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect('/booking');
});

Route::resource('booking', \App\Http\Controllers\AppointmentController::class)->only([
    'index', 'store', 'show', 'destroy', 'update', 'edit'
]);

Route::get('/listing', [\App\Http\Controllers\AppointmentListingController::class, 'index'])->name('listing');

Route::fallback(function () {
    return redirect('/');
});
