<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;



class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'identification' => 'required|unique:customers',
            'name'=> 'required',
            'phone' => 'required',
            'email' => 'unique:customers',
            'district' => 'required',
            'address' => 'required|max:200'
        ];
    }

    /**
     * Set Values Display Name
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'identification' => 'identificación',
            'name'=> 'nombre',
            'phone' => 'teléfono',
            'email' => 'correo',
            'district' => 'distrito',
            'address' => 'direccion'
        ];
    }


}
