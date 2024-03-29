<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Stylist;
use App\Notifications\ResetPasswordNotification;
use App\Http\Requests\PasswordResetRequest;
use App\Models\PasswordResetToken;
use App\Notifications\StylistPasswordResetNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
}
