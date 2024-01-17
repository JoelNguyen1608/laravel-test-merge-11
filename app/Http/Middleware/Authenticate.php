<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    /**
     * Validate the session token.
     */
    public function validateSessionToken($sessionToken)
    {
        $user = User::where('session_token', $sessionToken)
                    ->where('session_expiration', '>', now())
                    ->first();

        if ($user) {
            return true;
        }

        abort(response()->json(['message' => 'access_denied'], 401));
        return false;
    }
}
