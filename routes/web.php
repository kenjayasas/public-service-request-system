<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Public FAQ (no authentication required)
Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    
    // Dashboard - accessible by all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Management (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Service Categories - Admin only
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('categories', ServiceCategoryController::class);
    });
    
    // Service Requests
    Route::resource('requests', ServiceRequestController::class)->parameters([
        'requests' => 'serviceRequest'
    ]);
    Route::post('/requests/{serviceRequest}/assign-staff', [ServiceRequestController::class, 'assignStaff'])
        ->name('requests.assign-staff')
        ->middleware('role:admin');
    Route::get('/export/requests/pdf', [ServiceRequestController::class, 'exportPDF'])
        ->name('requests.export.pdf')
        ->middleware('role:admin');
    
    // Feedback
    Route::post('/requests/{serviceRequest}/feedback', [FeedbackController::class, 'store'])
        ->name('feedback.store');
    Route::get('/feedback', [FeedbackController::class, 'index'])
        ->name('feedback.index')
        ->middleware('role:admin');
    
    // FAQ Management - Admin only
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/faqs', [FaqController::class, 'adminIndex'])->name('faqs.admin');
        Route::get('/faqs/create', [FaqController::class, 'create'])->name('faqs.create');
        Route::post('/faqs', [FaqController::class, 'store'])->name('faqs.store');
        Route::get('/faqs/{faq}/edit', [FaqController::class, 'edit'])->name('faqs.edit');
        Route::put('/faqs/{faq}', [FaqController::class, 'update'])->name('faqs.update');
        Route::delete('/faqs/{faq}', [FaqController::class, 'destroy'])->name('faqs.destroy');
    });
    
    // Messaging System
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('index');
        Route::get('/unread', [MessageController::class, 'getUnreadCount'])->name('unread');
        Route::get('/{user}', [MessageController::class, 'show'])->name('show');
        Route::get('/{user}/poll', [MessageController::class, 'poll'])->name('poll');
        Route::post('/', [MessageController::class, 'store'])->name('store');
        Route::post('/{message}/read', [MessageController::class, 'markAsRead'])->name('read');
    });
});

// Include authentication routes
require __DIR__.'/auth.php';