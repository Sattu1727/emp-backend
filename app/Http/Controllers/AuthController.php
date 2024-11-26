<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use Carbon\Carbon;

class AuthController extends Controller
{
    // Admin registration
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:admin_loginid,email',
                'password' => 'required|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $admin = Admin::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Admin registered successfully.',
                'data' => $admin,
            ], 201); // Created
        } catch (\Exception $e) {
            Log::error('Error during registration: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred during registration.',
            ], 500); // Internal Server Error
        }
    }

    // Admin login
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $admin = Admin::where('email', $request->email)->first();

            if (!$admin || !Hash::check($request->password, $admin->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid email or password.',
                ], 401); // Unauthorized
            }

            return response()->json([
                'status' => true,
                'message' => 'Login successful.',
                'data' => $admin,
            ], 200); // OK
        } catch (\Exception $e) {
            Log::error('Error during login: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred during login.',
            ], 500); // Internal Server Error
        }
    }

    // Request password reset
    public function requestPasswordReset(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:admin_loginid,email',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $resetKey = Str::random(64);
            $admin = Admin::where('email', $request->email)->first();

            $admin->reset_key = $resetKey;
            $admin->reset_key_expires_at = Carbon::now()->addMinutes(30);
            $admin->save();

            // Send email
            Mail::raw("Your password reset key is: $resetKey", function ($message) use ($request) {
                $message->to($request->email)->subject('Password Reset Request');
            });

            return response()->json([
                'status' => true,
                'message' => 'Reset key sent to your email.',
            ], 200); // OK
        } catch (\Exception $e) {
            Log::error('Error during password reset request: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while requesting password reset.',
            ], 500); // Internal Server Error
        }
    }

    // Reset password
    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:admin_loginid,email',
                'reset_key' => 'required',
                'new_password' => 'required|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $admin = Admin::where('email', $request->email)->first();

            if (!$admin || $admin->reset_key !== $request->reset_key || Carbon::now()->greaterThan($admin->reset_key_expires_at)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid or expired reset key.',
                ], 401); // Unauthorized
            }

            $admin->password = Hash::make($request->new_password);
            $admin->reset_key = null;
            $admin->reset_key_expires_at = null;
            $admin->save();

            return response()->json([
                'status' => true,
                'message' => 'Password reset successfully.',
            ], 200); // OK
        } catch (\Exception $e) {
            Log::error('Error during password reset: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while resetting the password.',
            ], 500); // Internal Server Error
        }
    }
}
