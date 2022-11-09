<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
	public function redirect(): JsonResponse
	{
		$url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
		return response()->json(['url' => $url]);
	}

	public function callback(): JsonResponse
	{
		$user = Socialite::driver('google')->stateless()->user();

		$findUser = User::where('google_id', $user->id)->first();

		if ($findUser)
		{
			$token = auth()->login($findUser);

			return response()->json([
				'user'                         => auth()->user(),
				'access_token'                 => $token,
				'token_type'                   => 'bearer',
				'expires_in'                   => auth()->factory()->getTTL() * 60, ]);
		}

		$newUser = User::create([
			'name'     => $user->name,
			'email'    => $user->email,
			'google_id'=> $user->id,
			'password' => encrypt('123456dummy'),
		]);

		$token = auth()->login($newUser);

		return response()->json([
			'user'                         => auth()->user(),
			'access_token'                 => $token,
			'token_type'                   => 'bearer',
			'expires_in'                   => auth()->factory()->getTTL() * 60, ]);
	}
}
