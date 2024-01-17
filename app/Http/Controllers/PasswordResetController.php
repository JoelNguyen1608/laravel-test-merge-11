
<?php

namespace App\Http\Controllers;

use App\Models\Stylist;
use App\Models\PasswordResetToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Notifications\StylistPasswordResetNotification;

class PasswordResetController extends Controller
{
    public function requestPasswordReset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:stylists,email',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $email = $request->input('email');
        $stylist = Stylist::where('email', $email)->first();

        if (!$stylist) {
            return response()->json(['message' => 'Stylist not found.'], 404);
        }

        $token = Str::random(60);
        $expiration = now()->addHour();

        $passwordResetToken = new PasswordResetToken([
            'email' => $email,
            'token' => $token,
            'expiration' => $expiration,
            'used' => false,
            'stylist_id' => $stylist->id,
        ]);
        $passwordResetToken->save();

        $stylist->notify(new StylistPasswordResetNotification($token, $expiration));

        return response()->json([
            'reset_token' => $token,
            'token_expiration' => $expiration,
        ]);
    }
}
