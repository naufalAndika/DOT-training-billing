<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BillingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_name'  => 'required|string',
            'price'         => 'required|numeric',
            'discount'      => 'required|numeric',
            'pay_before'    => 'required|date',
            'email'         => 'required|email'
        ];
    }

    /**
     * Failed validation
     *
     * @param Validator $validator
     * @return void
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(rest_error($validator->errors()));
    }
}
