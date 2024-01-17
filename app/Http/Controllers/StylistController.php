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

    public function logout(Request $request): JsonResponse
    {
        try {
            $stylist = Stylist::findOrFail($request->stylist_id);
            $stylist->clearSessionToken();
            return response()->json(['logout_status' => true]);
        } catch (Exception $e) {
            return response()->json(['logout_status' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
