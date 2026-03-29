<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\MessageController;
use App\Models\ServiceCategory;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ─── Public Routes (No Auth Required) ────────────────────────────────────────

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Public: service categories (needed for create request form)
Route::get('/service-categories', function () {
    return response()->json(ServiceCategory::all());
});


// ─── Authenticated Routes (Requires Sanctum Token) ───────────────────────────

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user',    [AuthController::class, 'user']);

    // Profile update
    Route::put('/profile', function (Request $request) {
        $request->validate([
            'name'    => 'sometimes|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);
        $request->user()->update($request->only(['name', 'phone', 'address']));
        return response()->json($request->user()->fresh());
    });

    // ─── Citizen Routes ───────────────────────────────────────────────────
    Route::middleware('citizen')->group(function () {

        // Service Requests
        Route::get('/service-requests',         [ServiceRequestController::class, 'index']);
        Route::post('/service-requests',        [ServiceRequestController::class, 'store']);
        Route::get('/service-requests/{id}',    [ServiceRequestController::class, 'show']);
        Route::put('/service-requests/{id}',    [ServiceRequestController::class, 'update']);
        Route::delete('/service-requests/{id}', [ServiceRequestController::class, 'destroy']);

        // Feedback (API route uses service request ID in URL)
        Route::get('/feedback',                                    [FeedbackController::class, 'index']);
        Route::post('/service-requests/{id}/feedback',             [FeedbackController::class, 'store']);
        Route::get('/feedback/{id}',                               [FeedbackController::class, 'show']);

        // Messages
        Route::get('/messages',          [MessageController::class, 'index']);
        Route::post('/messages',         [MessageController::class, 'store']);
        Route::get('/messages/{userId}', [MessageController::class, 'show']);
    });

    // ─── Staff Routes ─────────────────────────────────────────────────────
    Route::middleware('staff')->group(function () {

        // Assigned Service Requests
        Route::get('/assigned-requests',      [ServiceRequestController::class, 'assignedIndex']);
        Route::put('/assigned-requests/{id}', [ServiceRequestController::class, 'updateStatus']);

        // Messages
        Route::get('/messages',          [MessageController::class, 'index']);
        Route::post('/messages',         [MessageController::class, 'store']);
        Route::get('/messages/{userId}', [MessageController::class, 'show']);
    });

    // ─── Admin Routes ─────────────────────────────────────────────────────
    Route::middleware('admin')->group(function () {

        // User Management
        Route::get('/users',         [AuthController::class, 'index']);
        Route::get('/users/{id}',    [AuthController::class, 'show']);
        Route::put('/users/{id}',    [AuthController::class, 'update']);
        Route::delete('/users/{id}', [AuthController::class, 'destroy']);

        // All Service Requests
        Route::get('/all-requests',          [ServiceRequestController::class, 'adminIndex']);
        Route::put('/all-requests/{id}',     [ServiceRequestController::class, 'adminUpdate']);
        Route::delete('/all-requests/{id}',  [ServiceRequestController::class, 'adminDestroy']);
        Route::post('/assign-request/{id}',  [ServiceRequestController::class, 'assignStaff']);

        // All Feedback
        Route::get('/all-feedback',     [FeedbackController::class, 'adminIndex']);
        Route::delete('/feedback/{id}', [FeedbackController::class, 'destroy']);

        // All Messages
        Route::get('/all-messages', [MessageController::class, 'adminIndex']);
    });

});
