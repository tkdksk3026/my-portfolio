<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\MenuItemController;

Route::get('/', function () {return view('welcome');});
Route::get('/dashboard', function () {return view('dashboard');})->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('schedules', ScheduleController::class)->only(['index','store','show','destroy' ]);
    Route::delete('/menu_items/{menuItem}', [MenuItemController::class, 'destroy'])->name('menu_items.destroy');
    Route::post('schedules/{schedule}/menu_items',[MenuItemController::class, 'store'])->name('menu_items.store');  
    Route::patch('/menu_items/{menuItem}/toggle',  [MenuItemController::class, 'toggle'])->name('menu_items.toggle');
    Route::post('/schedules/{schedule}/menu-items/reorder', [MenuItemController::class, 'reorder'])->name('menu-items.reorder');

    });

    
    require __DIR__.'/auth.php';