<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    // Route::get('schedules',[ScheduleController::class, index])->name('schedules.index')->middleware('auth');
    // Route::post('schedules',[ScheduleController::class, store])->name('schedules,store')->middleware('auth');
    // Route::get('schedules/{schedule}',[ScheduleController::class, show])->name('schedules,show')->middleware('auth');
    // Route::delete('schedules/{schedule}',[scheduleController::class,destroy])->name('schedules,destroy,destroy')->middleware('auth');
    
    Route::middleware('[auth]')->group(function(){

        Route::resource('schedules',ScheduleController::class)->only([
            'index','store','show','destroy'

        ]);
    });


}
