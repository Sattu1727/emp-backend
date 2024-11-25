<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Token;
use Illuminate\Support\Str;

class TokenController extends Controller
{
    // Generate a new token
    public function generateToken(Request $request)
    {
        $tokenString = Str::random(60);

        $expiresAt = Carbon::now()->addHour();

        $token = new Token();
        $token->token = $tokenString;
        $token->expires_at = $expiresAt;
        $token->save();

        return response()->json([
            'token' => $tokenString,
            'expires_at' => $expiresAt
        ], 201);
    }

    // Retrieve token details
    public function getToken(Request $request)
    {
        $tokenString = $request->query('token');

        if (!$tokenString) {
            return response()->json(['error' => 'Token parameter is required'], 400);
        }

        $token = Token::where('token', $tokenString)->first();

        if (!$token) {
            return response()->json(['error' => 'Token not found'], 404);
        }

        if (Carbon::now()->greaterThan($token->expires_at)) {
            return response()->json(['error' => 'Token has expired'], 410);
        }

        return response()->json([
            'token' => $token->token,
            'expires_at' => $token->expires_at
        ], 200);
    }
}