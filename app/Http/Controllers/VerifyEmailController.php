<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;

class VerifyEmailController extends Controller
{
	/**
	 * Mark the authenticated user's email address as verified.
	 *
	 * @param EmailVerificationRequest $request
	 *
	 * @return JsonResponse
	 */
	public function __invoke(EmailVerificationRequest $request): JsonResponse
	{
		if ($request->user()->hasVerifiedEmail())
		{
			return response()->json(
				[
					'message' => 'Given email is already verified.',
				],
				400
			);
		}

        $request->user()->markEmailAsVerified();

        event(new Verified($request->user()));

//		if ($request->user()->markEmailAsVerified())
//		{
//			event(new Verified($request->user()));
//		}

		return response()->json(
			[
				'message' => 'Verification complete.',
			]
		);
	}
}
