
<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordResetRequest;
use App\Models\User;
use App\Models\PasswordResetToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function requestPasswordReset(PasswordResetRequest $request)
    {
        $validatedData = $request->validated();

        $email = $validatedData['email'];
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $token = Str::random(60);
        $expiresAt = now()->addMinutes(config('auth.passwords.users.expire'));

        $user->passwordResetTokens()->create([
            'token' => $token,
            'expires_at' => $expiresAt,
            'status' => 'active',
        ]);

        $user->sendPasswordResetNotification($token);

        return response()->json(['token' => $token]);
    }
}
