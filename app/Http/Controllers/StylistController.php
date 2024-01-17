
<?php

namespace App\Http\Controllers;

use App\Models\Stylist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class StylistController extends Controller
{
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
