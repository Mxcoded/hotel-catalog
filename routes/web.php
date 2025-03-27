<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbackController;


// Home route or landing page
Route::get('/', [RoomController::class, 'index'])->name('home');

Route::get('/feedback', [FeedbackController::class, 'create'])->name('feedback.create');
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
// Define the route to display the feedback report
Route::get('/feedback-report', [FeedbackController::class, 'showReport'])->name('feedback.report');

// Full resource route for rooms, relying on middleware defined in RoomController
Route::resource('rooms', RoomController::class);
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';