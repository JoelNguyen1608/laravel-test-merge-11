
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthenticateSessionToken
{
    public function handle(Request $request, Closure $next)
    {
        $sessionToken = $request->header('session_token');

        if (!$sessionToken) {
            return new JsonResponse(['message' => 'access_denied'], 401);
        }

        $user = User::where('session_token', $sessionToken)
                    ->where('session_expiration', '>', now())
                    ->first();

        if (!$user) {
            return new JsonResponse(['message' => 'access_denied'], 401);
        }

        $request->attributes->set('user', $user);

        return $next($request);
    }
}
