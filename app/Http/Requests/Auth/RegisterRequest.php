<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'name'     => 'required|min:3|max:15',
			'email'    => 'required|email|unique:users,email',
			'password' => 'required|confirmed|min:8|max:15',
		];
	}
}
