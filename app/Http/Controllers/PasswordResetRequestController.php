<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Mail\ResetPasswordMailable;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;  // this is for make a Random token in that time User want to send special Token to his Gmail

class PasswordResetRequestController extends Controller
{
	public function sendEmail(ResetPasswordRequest $request): JsonResponse // this is the function to send mail and inside that there are another function
	{
		if (!$this->validateEmail($request->email))  // this is validated to fail send mail or true
		{
			return $this->failedResponse();
		}
		$this->send($request->email);  // this function sends mail
		return $this->successResponse();
	}

	public function send($email)  // this function sends mail
	: void
	{
		$token = $this->createToken($email);
		Mail::to($email)->send(new ResetPasswordMailable($token, $email));  // token is important in send mail
	}

	public function createToken($email)  // this function  get your request email that there are or not to send mail
	{
		$oldToken = DB::table('password_resets')->where('email', $email)->first();

		if ($oldToken)
		{
			return $oldToken->token;
		}

		$token = Str::random(40);     // create a random to send
		$this->saveToken($token, $email);   // save token and email
		return $token;
	}

	public function saveToken($token, $email)  // this function save new password in password_resets of table
	: void
	{
		DB::table('password_resets')->insert([
			'email'      => $email,
			'token'      => $token,
			'created_at' => Carbon::now(),
		]);
	}

	public function validateEmail($email): bool  // this function get your email from database
	{
		return (bool)User::where('email', $email)->first();
	}

// Success or Failed send Request

	public function failedResponse(): JsonResponse
	{
		return response()->json([
			'error' => 'Email does\'t found on our database',
		], Response::HTTP_NOT_FOUND);
	}

	public function successResponse(): JsonResponse
	{
		return response()->json([
			'data' => 'Reset Email is send successfully, please check your inbox.',
		], Response::HTTP_OK);
	}
}
