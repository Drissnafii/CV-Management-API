<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // In Laravel 12, middleware should be registered in bootstrap/app.php
        // We can't use $this->middleware() in the controller
    }

    /**
     * Register a new user.
     *
     * @param  \App\Http\Requests\RegisterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
// In your AuthController.php

// For the register method
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::guard('api')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => config('jwt.ttl') * 60 // Use config helper to get TTL value
            ]
        ], 201);
    }

    /**
     * Authenticate a user and return a JWT.
     *
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid login credentials'
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => Auth::guard('api')->user()
            ]
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'User logged out successfully'
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        // Use JWTAuth facade to refresh the token
        $newToken = JWTAuth::parseToken()->refresh();

        return $this->respondWithToken($newToken);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'User logged in successfully',
            'data' => [
                'user' => Auth::guard('api')->user(),
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => config('jwt.ttl') * 60 // Using the config helper to get ttl 
            ]
        ]);
    }
}
