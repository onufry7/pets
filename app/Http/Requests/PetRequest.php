<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PetRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'status' => 'required|string|in:available,pending,sold',
            'category_name' => 'required|string|max:255',
            'tag_name' => 'required|string|max:255',
            'photo' => 'required|file|image|max:2048', // max 2MB
        ];
    }
}
