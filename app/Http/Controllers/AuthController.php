<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
	public function register(RegisterRequest $request): JsonResponse
	{
		$user = User::create([
			'name'     => $request->name,
			'email'    => $request->email,
			'password' => bcrypt($request->password),
		]);

		event(new Registered($user));
		$token = auth()->login($user);

		return response()->json([
			'status'       => 'success',
			'user'         => auth()->user(),
			'access_token' => $token,
			'token_type'   => 'bearer',
			'expires_in'   => auth()->factory()->getTTL() * 60,
		]);
	}

	public function verify(EmailVerificationRequest $request): JsonResponse
	{
		$request->fulfill();

		return response()->json('User verified successfully');
	}

	public function login(LoginRequest $request): JsonResponse
	{
		$token = auth()->attempt($request->validated(), $request->has('remember'));

		if (!$token)
		{
			return response()->json('User does not exists', 400);
		}

		return response()->json([
			'user'         => auth()->user(),
			'access_token' => $token,
			'token_type'   => 'bearer',
			'expires_in'   => auth()->factory()->getTTL() * 60,
		]);
	}

	public function logout(): JsonResponse
	{
		auth()->logout();

		return response()->json(['message' => 'Successfully logged out']);
	}
}
