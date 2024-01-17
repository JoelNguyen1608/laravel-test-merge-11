<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Stylist;
use App\Notifications\ResetPasswordNotification;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\LoginRequest;
use App\Models\PasswordResetToken;
use App\Notifications\StylistPasswordResetNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class ForgotPasswordController extends Controller
{
    protected function sendResetEmail(Stylist $stylist, $token)
    {
        $expiration = now()->addMinutes(config('auth.passwords.users.expire'));
        $stylist->notify(new ResetPasswordNotification($token, $expiration));
    }

    public function requestPasswordReset(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            $email = $request->input('email');
            $stylist = Stylist::where('email', $email)->first();

            if (!$stylist) {
                return response()->json(['message' => 'Stylist not found'], 404);
            }

            $tokenRecord = $stylist->generatePasswordResetToken();
            $stylist->notify(new StylistPasswordResetNotification($tokenRecord->token, $tokenRecord->expiration));

            return response()->json(['status' => 200, 'message' => 'Password reset email sent successfully.'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to send password reset email', 'error' => $e->getMessage()], 500);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password_hash)) {
            $this->logFailedLoginAttempt($request->email);
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $keepSession = $request->input('keep_session', false);
        $sessionExpiration = $keepSession ? now()->addDays(90) : now()->addHours(24);

        $user->session_token = Str::random(60);
        $user->session_expiration = $sessionExpiration;
        $user->save();

        return response()->json(['session_token' => $user->session_token]);
    }

    public function loginWithFailureHandling(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->validated())) {
            $this->logFailedLoginAttempt($request->email);
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Handle successful login if necessary
        // ... (The rest of the successful login handling code should be here)
    }

    protected function logFailedLoginAttempt(string $email): void
    {
        Log::warning("Failed login attempt for email: {$email}", ['timestamp' => now()]);
    }
}
