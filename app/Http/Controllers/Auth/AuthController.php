
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $keepSession = $request->input('keep_session', false);
            $sessionExpiration = $keepSession ? Carbon::now()->addDays(90) : Carbon::now()->addHours(24);
            $user->session_token = Str::random(60);
            $user->session_expiration = $sessionExpiration;
            $user->save();

            return response()->json(['session_token' => $user->session_token]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}
