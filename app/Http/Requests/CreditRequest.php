<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
             'total' => 'required|numeric|min:1|max:1000000|gte:first_payment',
             'first_payment' => 'required|numeric|min:0|max:1000000|lte:total',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'total' => 'total',
            'first_payment' => 'Prima',
            'balance' => 'Total del credito',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'total.gte' => 'El Total no puede ser menor que la prima',
            'first_payment.lte' => 'La Prima no puede ser mayor que el total'
        ];
    }
}
