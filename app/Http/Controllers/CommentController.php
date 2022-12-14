<?php

namespace App\Http\Controllers;

use App\Events\CommentEvent;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Notification;
use App\Events\NotificationEvent;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
	public function index(Quote $quote): JsonResponse
	{
		return response()->json(['comments' => $quote->comments], 200);
	}

	public function store(StoreCommentRequest $request, Quote $quote): JsonResponse
	{
		$comment = $quote->comments()->create([
			'user_id'  => jwtUser()->id,
			'body'     => $request->body,
		]);

		if (jwtUser()->id !== $request->to_id)
		{
			$notification = Notification::create([
				'to'       => $request->to_id,
				'from'     => User::find(jwtUser()->id),
				'type'     => 'comment',
				'read'     => false,
			]);
			event(new NotificationEvent($notification));
		}

		event(new CommentEvent($comment));

		return response()->json(['status' => 'Comment added successfully'], 200);
	}
}
