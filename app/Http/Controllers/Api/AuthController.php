<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
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
			'message'      => 'User created successfully',
			'user'         => auth()->user(),
			'access_token' => $token,
			'token_type'   => 'bearer',
			'expires_in'   => auth()->factory()->getTTL() * 60,
		]);
	}

	public function verify(EmailVerificationRequest $request): JsonResponse
    {
		$request->fulfill();

//		$user = User::find($request->route('id'));
//
//		if ($user->hasVerifiedEmail())
//		{
//			return redirect(env('FRONT_URL') . '/email/verify/already-success');
//		}
//
//		if ($user->markEmailAsVerified())
//		{
//			event(new Verified($user));
//		}
//
//		return redirect(env('FRONT_URL') . '/email/verify/success');
//		$user = User::find($request->route('id'));
//		if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification())))
//		{
//			throw new AuthorizationException();
//		}
//		if ($user->hasVerifiedEmail())
//		{
//			$user->markEmailAsVerified();
//
//			event(new Verified($user));
//		}
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
