<?php

namespace App\Http\Requests\Image;

use App\Library\JsonResponseData;
use App\Library\Message;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ImageCreateRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'image_file'  => 'required|image|max:10000',
            'alt_tag'     => 'string|required|max:140',
            'description' => 'string|required|max:140',
            'is_hero'     => 'boolean|required',
        ];
    }

    public function messages(): array {
        return [
            'image_file.required'  => 'Please select a file',
            'image_file.image'     => 'File type not recognized, please select a valid image',
            'image_file.max'       => 'Upload max 10M',
            'alt_tag.required'     => 'Please provide a title',
            'alt_tag.max'          => 'Maximum Length is 140 characters',
            'description.required' => 'Please provide a short description',
            'description.max'      => 'Maximum Length is 140 characters',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json(
            JsonResponseData::formatData(
                $this,
                'Validation Failed',
                Message::MESSAGE_ERROR,
                [
                    'errors' => $validator->errors(),
                    'status' => true,
                ])
            , 422));
    }
}
