<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddEmailRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Email;
use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

		return response()->json('User registered successfully', 200);
	}

	public function verify(Request $request): JsonResponse
	{
		$user = User::find($request->route('id'));

		if ($user->emails)
		{
			foreach ($user->emails as $email)
			{
				$email->email_verified_at = Carbon::now();
			}
		}

		if ($user->hasVerifiedEmail())
		{
			return response()->json('User is already verified', 200);
		}

		if ($user->markEmailAsVerified())
		{
			event(new Verified($user));
		}
		return response()->json('User verified successfully', 200);
	}

	public function login(LoginRequest $request): JsonResponse
	{
		$field = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'name' : 'email';

		$userEmail = $request->email;

		$emails = Email::all();

		foreach ($emails as $email)
		{
			if ($email->email === $request->email)
			{
				$user = User::find($email->user_id);
				$userEmail = $user->email;
			}
		}

		$authenticated = auth()->attempt(
			[
				$field         => $userEmail,
				'password'     => $request->password,
			]
		);

		if (!$authenticated)
		{
			return response()->json('Wrong email or password', 401);
		}

		$payload = [
			'exp' => Carbon::now()->addMinutes(60)->timestamp,
			'uid' => User::where('email', '=', $userEmail)->first()->id,
		];

		$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');

		$cookie = cookie('access_token', $jwt, 60, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

		return response()->json(['user' => auth()->user()], 200)->withCookie($cookie);
	}

	public function user(): JsonResponse
	{
		return response()->json(
			[
				'user'    => jwtUser(),
			],
			200
		);
	}

	public function update(Request $request): JsonResponse
	{
		$user = jwtUser();

		if ($request->hasFile('image'))
		{
			$file_name = '/images/' . time() . '_' . $request->file('image')->getClientOriginalName();
			$request->file('image')->storePubliclyAs('public', $file_name);
			$user->update(['image' => $file_name]);
		}

		if ($request->password)
		{
			$user->update([
				'password' => bcrypt($request->password),
			]);
		}

		if ($request->name)
		{
			$user->update([
				'name' => $request->name,
			]);
		}

		return response()->json([
			'user'  => $user,
		], 200);
	}

	public function logout(): JsonResponse
	{
		$cookie = cookie('access_token', '', 0, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

		return response()->json('success', 200)->withCookie($cookie);
	}

	public function addEmail(AddEmailRequest $request): JsonResponse
	{
		$email = Email::create([
			'user_id'     => jwtUser()->id,
			'email'       => $request->email,
		]);

		jwtUser()->sendEmailVerificationNotification();

		return response()->json(['email' => $email], 200);
	}

	public function deleteEmail(Email $email): JsonResponse
	{
		$email->delete();

		return response()->json(['status' => 'Email deleted successfully!'], 200);
	}

	public function makePrimaryEmail(Email $email): JsonResponse
	{
		$user = jwtUser();

		$user->update([
			'email'  => $email->email,
		]);

		$email->delete();

		return response()->json(['status' => 'Email become primary successfully!'], 200);
	}
}
