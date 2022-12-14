<?php

namespace App\Http\Controllers;

use App\Events\LikeEvent;
use App\Events\NotificationEvent;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
	public function like(Quote $quote, Request $request): JsonResponse
	{
		$like = Like::create([
			'quote_id' => $quote->id,
			'user_id'  => jwtUser()->id,
		]);

		if (jwtUser()->id !== $request->to_id)
		{
			$notification = Notification::create([
				'to'       => $request->to_id,
				'from'     => User::find(jwtUser()->id),
				'type'     => 'like',
				'read'     => false,
			]);
			event(new NotificationEvent($notification));
		}

		event(new LikeEvent($like));

		return response()->json(['message' => 'Quote is liked successfully']);
	}

	public function unlike(Quote $quote): JsonResponse
	{
		$like = Like::where('quote_id', $quote->id)->where('user_id', jwtUser()->id)->first();

		$like->delete();

		return response()->json(['message' => 'Quote is unliked successfully']);
	}

	public function getLikes(Quote $quote): JsonResponse
	{
		$like = Like::where('quote_id', $quote->id)->count();

		return response()->json(['like' => $like]);
	}

	public function checkLikes(Quote $quote): JsonResponse
	{
		$like = Like::where('quote_id', $quote->id)->where('user_id', jwtUser()->id)->count();

		return response()->json(['like' => $like]);
	}
}
