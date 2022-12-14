<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name_en'        => 'required',
            'name_ka'        => 'required',
            'image'          => 'required|image',
            'movie_id'          => 'required',
        ];
    }
}
