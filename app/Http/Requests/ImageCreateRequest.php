<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageCreateRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'image_file'  => 'required|image|max:10000',
            'title'       => 'string|required|max:140',
            'description' => 'string|required|max:140',
        ];
    }

    public function messages(): array {
        return [
            'image_file.required'  => 'Please select a file',
            'image_file.image'     => 'File type not recognized, please select a valid image',
            'image_file.max'       => 'Upload max 10M',
            'title.required'       => 'Please provide a title',
            'title.max'            => 'Maximum Length is 140 characters',
            'description.required' => 'Please provide a short description',
            'description.max'      => 'Maximum Length is 140 characters',
        ];
    }
}
