<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuoteRequest extends FormRequest
{
	public function rules()
	{
		return [
			'name_en'           => 'required',
			'name_ka'           => 'required',
			'movie_id'          => 'required',
		];
	}
}
