
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Stylist;
use App\Notifications\ResetPasswordNotification;

class ForgotPasswordController extends Controller
{
    protected function sendResetEmail(Stylist $stylist, $token)
    {
        $expiration = now()->addMinutes(config('auth.passwords.users.expire'));
        $stylist->notify(new ResetPasswordNotification($token, $expiration));
    }
}
