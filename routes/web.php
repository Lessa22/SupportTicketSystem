<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TicketMessageController;


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

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('tickets', TicketController::class);

    Route::patch(
    '/tickets/{ticket}/status',
    [TicketController::class, 'changeStatus']
)->name('tickets.changeStatus');
});
Route::middleware('auth')->group(function () {

    Route::get(
        '/notifications',
        [NotificationController::class,'index']
    )->name('notifications.index');

});
Route::middleware('auth')->group(function () {

    Route::get('/dashboard',
        [DashboardController::class,'index'])
        ->name('dashboard');

});

Route::middleware(['auth','role:admin'])->group(function(){

    Route::get('/admin/users',
        [AdminController::class,'users'])
        ->name('admin.users');

    Route::put('/admin/users/{user}/role',
        [AdminController::class,'updateRole'])
        ->name('admin.role');

});
Route::post(
'/tickets/{ticket}/messages',
[TicketMessageController::class,'store']
)->name('tickets.messages.store');

require __DIR__.'/auth.php';
