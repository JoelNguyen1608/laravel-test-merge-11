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

// This route allows a stylist to update their password
Route::post('/stylist/update-password', [StylistController::class, 'updatePassword'])->name('stylists.update-password');

// This route allows a stylist to logout
// The DELETE method is more appropriate for logout action as per the requirement
Route::delete('/stylists/logout', [StylistController::class, 'logout'])->middleware('auth:sanctum')->name('stylists.logout');

// Remove the old POST route for stylist logout as it is replaced by the DELETE route
// Route::middleware('auth:sanctum')->post('/stylist/logout', [Controller::class, 'logoutStylist'])->name('stylist.logout');

// This route allows a stylist to request a password reset
Route::post('/api/stylists/password_reset', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
    ]);

    if ($validator->fails()) {
        $errors = $validator->errors();
        if ($errors->has('email')) {
            return response()->json([
                'status' => 400,
                'message' => $errors->first('email'),
            ], 400);
        }
    }

    // Assuming the existence of a method in ForgotPasswordController to handle the password reset request
    $response = app(ForgotPasswordController::class)->requestPasswordReset($request);

    // The response should be returned from the ForgotPasswordController method
    return $response;
})->middleware('api');
