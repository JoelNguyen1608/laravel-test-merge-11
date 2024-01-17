
<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Middleware\Authenticate;
use App\Models\Stylist;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $authenticateMiddleware;

    public function __construct()
    {
        $this->authenticateMiddleware = new Authenticate();
        $this->middleware(function ($request, $next) {
            $sessionToken = $request->header('session_token');
            if ($sessionToken) {
                $validationResult = $this->authenticateMiddleware->validateSessionToken($sessionToken);
                if (!$validationResult) {
                    return response()->json(['message' => 'access_denied'], 401);
                }
            }
            return $next($request);
        });
    }

    public function logoutStylist(int $stylist_id): JsonResponse
    {
        try {
            $stylist = Stylist::findOrFail($stylist_id);
            $stylist->session_token = null;
            $stylist->token_expiration = Carbon::now();
            $stylist->save();

            return response()->json(['logout_status' => true]);
        } catch (\Exception $e) {
            return response()->json(['logout_status' => false], 500);
        }
    }
}
