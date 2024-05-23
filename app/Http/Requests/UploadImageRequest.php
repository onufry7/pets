<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'petId' => 'required|integer',
            'additionalMetadata' => 'nullable|string',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
