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
        return [
            'name'          => 'required|max:50',
            'price'         => 'required',
            'categories'    => 'required',
            'time_for_work' => 'required',
            'access'        => 'nullable',
            'desc'          => 'required',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);

        Session::forget('job.files');
    }

}
