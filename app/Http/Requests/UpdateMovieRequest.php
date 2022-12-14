<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name_en'        => 'required',
            'name_ka'        => 'required',
            'director_en'    => 'required',
            'director_ka'    => 'required',
            'description_en' => 'required',
            'description_ka' => 'required',
            'budget'         => 'required',
            'year'           => 'required',
            'genre'          => 'required',
        ];
    }
}
