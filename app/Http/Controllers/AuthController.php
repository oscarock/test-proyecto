<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function authenticate(Request $request)
	{

        $credentials = $request->only('email', 'password');

		try {
			if (!Auth::attempt($credentials)) {
				throw ValidationException::withMessages([
					'email' => ['Invalid credentials'],
				]);
			}

			$user = Auth::user();
			$token = JWTAuth::fromUser($user);

			return response()->json([
				"meta" => [
					'success' => true,
					'errors' => []
				],
				"data" => [
					"token"=> $token,
					"minutes_to_expire" => JWTAuth::factory()->getTTL()
				]
			]);
		} catch (\Exception $e) {
			return response()->json([
				"meta" => [
					'success' => false,
					'errors' => "Password incorrect for: {$request->email}"
				],
			], 401);
		}
    }

		/**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
			$validator = Validator::make($request->all(), [
				'name' => 'required|string|max:255',
				'email' => 'required|string|email|max:255|unique:users',
				'password' => 'required|string|min:8|confirmed',
			]);

			if ($validator->fails()) {
				return response()->json(['errors' => $validator->errors()], 422);
			}

			$user = User::create([
				'name' => $request->name,
				'email' => $request->email,
				'password' => Hash::make($request->password),
			]);

			return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }
}
