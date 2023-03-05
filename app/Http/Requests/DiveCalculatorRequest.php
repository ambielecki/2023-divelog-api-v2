<?php

namespace App\Http\Requests;

use App\Library\JsonResponseData;
use App\Library\Message;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DiveCalculatorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'dive_1_depth'     => 'numeric|nullable',
            'dive_1_time'      => 'numeric|nullable',
            'surface_interval' => 'numeric|nullable',
            'dive_2_depth'     => 'numeric|nullable',
            'dive_2_time'      => 'numeric|nullable',
        ];
    }

    /*
     * from: https://omidioraemmanuel.medium.com/how-to-return-json-from-laravel-form-request-validation-errors-d3c419cce8e0
     * forces all responses to json for validation - does not need Accepts: application/json header
     */
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
