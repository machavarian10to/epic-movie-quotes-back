<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
	public function redirect(): JsonResponse
	{
		$url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
		return response()->json(['url' => $url], 200);
	}

	public function callback(): JsonResponse
	{
		$user = Socialite::driver('google')->stateless()->user();

		$findUser = User::where('google_id', $user->id)->first();

		if ($findUser)
		{
			$payload = [
				'exp' => Carbon::now()->addMinutes(60)->timestamp,
				'uid' => User::where('email', '=', $findUser->email)->first()->id,
			];

			$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');

			$cookie = cookie('access_token', $jwt, 60, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

			return response()->json(['user' => $findUser])->withCookie($cookie);
		}

		$newUser = User::create([
			'name'             => $user->name,
			'email'            => $user->email,
			'image'            => $user->avatar,
			'google_id'        => $user->id,
			'password'         => encrypt('1234dummy'),
		]);

		$payload = [
			'exp' => Carbon::now()->addMinutes(60)->timestamp,
			'uid' => User::where('email', '=', $newUser->email)->first()->id,
		];

		$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');

		$cookie = cookie('access_token', $jwt, 60, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

		return response()->json(['user' => $newUser], 200)->withCookie($cookie);
	}
}
