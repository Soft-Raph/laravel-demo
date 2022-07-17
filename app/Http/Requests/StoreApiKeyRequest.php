<?php

namespace App\Http\Requests;

use App\Helpers\ResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreApiKeyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'base_url' => 'nullable',
            'description' => 'nullable',
            'service' => 'required',
            'type' => 'required|in:v4,random',
            'username' => 'required|unique:api_keys,username',
            'password' => 'required|confirmed'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $error = $validator->errors()->first();
        throw new HttpResponseException(
            ResponseHelper::error(422, $error)
        );
    }
}
