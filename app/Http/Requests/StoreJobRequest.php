<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Session;

class StoreJobRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->get('draft')) {
            return [
                'name' => 'required|max:50',
            ];
        }

        return [
            'name' => 'required|max:50',
            'price' => 'required|digits_between:1,6|integer',
            'categories' => 'required',
            'time_for_work' => 'required',
            'access' => 'nullable',
            'instructions' => 'nullable',
            'desc' => 'required',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);

        Session::forget('job.files');
    }

}
