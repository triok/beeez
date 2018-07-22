<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'body'   => 'required',
            'parent' => 'nullable|exists:comments,id',
            'job'    => 'exists:jobs,id',
        ];
    }
}
