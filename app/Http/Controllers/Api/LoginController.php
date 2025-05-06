<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        // Set validation rules
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Get credentials from the request
        $credentials = $request->only('username', 'password');

        // Attempt to authenticate the user using the 'api' guard
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            // If authentication fails, return unauthorized response
            return response()->json([
                'success' => false,
                'message' => 'Username atau Password Anda salah',
            ], 401);
        }

        // If authentication is successful, return success response with token and user data
        return response()->json([
            'success' => true,
            'user' => Auth::guard('api')->user(),
            'token' => $token,
        ], 200);
    }
}
