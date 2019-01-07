<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'name'           => 'required|unique:organizations,name,slug|max:200',
            'ownership'      => 'required|max:200',
            'ohrn'           => 'required|digits_between:13,15|integer|unique:organizations',
            'inn'            => 'required|digits_between:10,12|integer|unique:organizations',
            'bic'            => 'nullable|digits:9|integer',
            'curaccount'     => 'nullable|digits:20|integer',
            'coraccount'     => 'nullable|digits:20|integer',
            'kpp'            => 'required_if:ownership,"organization"|nullable|digits:9|integer',
            'contact_person' => 'required|max:200',
            'email'          => 'required|email|max:200',
            'logo'           => 'nullable|image|mimes:jpeg,jpg,png,gif',
        ];
    }
}
