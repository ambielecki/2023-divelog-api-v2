<?php

namespace App\Http\Requests\DiveLog;

use App\Library\JsonResponseData;
use App\Library\Message;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DiveLogRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array {
        return [
            'dive_number'          => 'numeric',
            'location'             => 'string|max:255',
            'dive_site'            => 'string|max:255',
            'buddy'                => 'string|max:255|nullable',
            'date_time'            => 'date',
            'max_depth_ft'         => 'numeric',
            'bottom_time_min'      => 'numeric',
            'surface_interval_min' => 'numeric|nullable',
            'used_computer'        => 'boolean',
            'description'          => 'string|max:2000|nullable',
            'notes'                => 'string|max:2000|nullable',
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
