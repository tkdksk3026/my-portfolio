<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScheduleController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//認証(auth)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route::get('schedules',[ScheduleController::class, 'index'])->name('schedules.index')->middleware('auth');
    // Route::post('schedules',[ScheduleController::class, 'store'])->name('schedules.store')->middleware('auth');
    // Route::get('schedules/{schedule}',[ScheduleController::class, 'show'])->name('schedules.show')->middleware('auth');
    // Route::delete('schedules/{schedule}',[ScheduleController::class,'destroy'])->name('schedules.destroy')->middleware('auth');

    Route::resource('schedules', ScheduleController::class)->only([
            'index','store','show','destroy'

        ]);
    });

    require __DIR__.'/auth.php';