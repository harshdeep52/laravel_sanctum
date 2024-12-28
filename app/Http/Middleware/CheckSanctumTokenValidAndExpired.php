<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CheckSanctumTokenValidAndExpired
{
    public function handle(Request $request, Closure $next)
    {
        // Retrieve the token from the Authorization header
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 400);
        }

        // Find the token in the database
        $sanctumToken = PersonalAccessToken::findToken($token);

        if (!$sanctumToken || !($sanctumToken->tokenable instanceof \App\Models\User)) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        // Check if the token has expired (you can set your own expiration logic)
        // $expiryTime = Carbon::parse($sanctumToken->created_at)->addMinutes(5); // 5 minutes expiry
        $expiryTime = Carbon::parse($sanctumToken->created_at)->addMinutes(env('SANCTUM_TOKEN_EXPIRY', 60));

        if (Carbon::now()->greaterThan($expiryTime)) {
            return response()->json(['message' => 'Token has expired'], 401);
        }

        // Set the user on the request, if token is valid
        Auth::setUser($sanctumToken->tokenable);

        return $next($request);
    }
}
