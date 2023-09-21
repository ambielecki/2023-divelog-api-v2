<?php

namespace App\Http\Requests\DiveLog;

use App\Library\Constants;
use App\Library\JsonResponseData;
use App\Library\Message;
use App\Models\DiveLog;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class DiveLogIndexRequest extends FormRequest
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
