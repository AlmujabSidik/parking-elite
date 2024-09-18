<?php

use App\Http\Controllers\CalendarController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CalendarController::class, 'index'])->name('calendar.index');
Route::post('/', [CalendarController::class, 'store'])->name('calendar.store');
