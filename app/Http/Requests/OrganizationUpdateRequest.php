<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationUpdateRequest extends FormRequest
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
            'ownership' => 'required|max:200',
            'inn' => 'required|max:200',
            'contact_person' => 'required|max:200',
            'email' => 'required|max:200',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif',
        ];
    }
}
