<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
{
	public function rules()
	{
		return [
			'to_id' => 'required',
		];
	}
}
