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
        try {
            // Generate a random token string
            $tokenString = Str::random(60);
            $expiresAt = Carbon::now()->addHour();

            // Create and save the token
            $token = new Token();
            $token->token = $tokenString;
            $token->expires_at = $expiresAt;
            $token->save();

            // Return the created token details
            return response()->json([
                'status' => 'success',
                'code' => 201,
                'message' => 'Token created successfully.',
                'data' => [
                    'token' => $tokenString,
                    'expires_at' => $expiresAt
                ]
            ], 201);
        } catch (\Exception $e) {
            // Return error response
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'An error occurred while creating the token.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Retrieve token details
    public function getToken(Request $request)
    {
        try {
            // Get token from query parameters
            $tokenString = $request->query('token');

            // Validate that the token is provided
            if (!$tokenString) {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Token parameter is required.'
                ], 400);
            }

            // Find the token in the database
            $token = Token::where('token', $tokenString)->first();

            // If token does not exist
            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Token not found.'
                ], 404);
            }

            // Check if the token is expired
            if (Carbon::now()->greaterThan($token->expires_at)) {
                return response()->json([
                    'status' => 'error',
                    'code' => 410,
                    'message' => 'Token has expired.'
                ], 410);
            }

            // Return the token details
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Token retrieved successfully.',
                'data' => [
                    'token' => $token->token,
                    'expires_at' => $token->expires_at
                ]
            ], 200);
        } catch (\Exception $e) {
            // Return error response
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'An error occurred while retrieving the token.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
