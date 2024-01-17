<?php

namespace App\Http\Controllers;

use App\Models\Stylist;
use App\Models\PasswordResetToken;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Exception;

class StylistController extends Controller
{
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $validated = $request->validated();
        $stylist = Stylist::find($validated['stylist_id']);
        $token = PasswordResetToken::validateToken($validated['token'], $validated['stylist_id']);

        if (!$stylist || !$token) {
            return response()->json(['password_update_status' => 'failed'], 400);
        }

        $stylist->updatePassword($validated['new_password']);
        $token->markAsUsed();

        return response()->json(['password_update_status' => 'success'], 200);
    }

    public function validateSession(Request $request): JsonResponse
    {
        $session_token = $request->input('session_token');

        if (empty($session_token)) {
            return response()->json(['message' => 'Session token is required.'], 401);
        }

        $stylist = Stylist::where('session_token', $session_token)->first();

        if (!$stylist || $stylist->token_expiration < now()) {
            return response()->json(['message' => 'Session token is invalid or expired.'], 401);
        }

        return response()->json(['message' => 'Session is valid.'], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            // Check for session token in the request header first, then fallback to request body
            $sessionToken = $request->header('Authorization') ?? $request->input('session_token');
            
            if (!$sessionToken) {
                return response()->json(['message' => 'Session token is required'], 401);
            }

            $stylist = Stylist::where('session_token', $sessionToken)->first();
            if (!$stylist || $stylist->token_expiration < now()) {
                return response()->json(['message' => 'Invalid or expired session token'], 401);
            }

            $stylist->clearSessionToken();

            return response()->json(['message' => 'Logout successful.'], 200);
        } catch (Exception $e) {
            return response()->json(['logout_status' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
