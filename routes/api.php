<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StylistController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Validator;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned to the "api" middleware group. Enjoy building your API!
|
*/

// This route allows a user to request a password reset
Route::post('/password-reset', [PasswordResetController::class, 'requestPasswordReset']);

// This route returns the authenticated user's details
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// This route handles the POST request for creating a stylist request
Route::middleware('auth:sanctum')->post('/stylist/request', [StylistController::class, 'createStylistRequest'])
    ->name('stylist.create-request');

// This route allows a stylist to update their password
Route::post('/stylist/update-password', [StylistController::class, 'updatePassword'])->name('stylists.update-password');

// This route allows a stylist to update their password using a token
// The route is protected by the 'api' middleware
Route::put('/api/stylists/password_update', [StylistController::class, 'updateStylistPassword'])->name('stylists.password-update');

// This route allows a stylist to logout
// The DELETE method is more appropriate for logout action as per the requirement
Route::delete('/stylists/logout', [StylistController::class, 'logout'])->middleware('auth:sanctum')->name('stylists.logout');

// This route is for session validation for a stylist
// The route is protected by the 'auth:sanctum' middleware to ensure that only authenticated users can access it.
Route::middleware('auth:sanctum')->get('/stylists/session_validation', [StylistController::class, 'validateSession']);

// This route allows a stylist to request a password reset
// The route is protected by the 'api' middleware
Route::post('/api/stylists/password_reset', [PasswordResetController::class, 'requestPasswordReset'])->middleware('api');

// Hair Stylist Login route
Route::post('/stylists/login', [StylistController::class, 'login']);

// Note: The 'logoutStylist' method will be implemented in the Controller class.
