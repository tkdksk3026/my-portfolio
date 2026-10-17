<?php

use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('schedules', ScheduleController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::delete('/menu_items/{menuItem}', [MenuItemController::class, 'destroy'])->name('menu_items.destroy');
    Route::post('schedules/{schedule}/menu_items', [MenuItemController::class, 'store'])->name('menu_items.store');
    Route::patch('/menu_items/{menuItem}/toggle', [MenuItemController::class, 'toggle'])->name('menu_items.toggle');
    Route::post('/schedules/{schedule}/menu-items/reorder', [MenuItemController::class, 'reorder'])->name('menu-items.reorder');
    Route::get('/menu_items/{menuItem}/edit', [MenuItemController::class, 'edit'])->name('menu_items.edit');
    Route::patch('/menu_items/{menuItem}', [MenuItemController::class, 'update'])->name('menu_items.update');

});

require __DIR__.'/auth.php';
