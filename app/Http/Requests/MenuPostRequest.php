<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuPostRequest extends FormRequest
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
            //
            'resource_name' => 'required',
            'resource_url' => 'required',
        ];
    }

    public function messages()
    {
        return [
            "resource_name.required"         =>           '请输入resource_name',
            "resource_url.required"          =>           '请输入resource_url',
        ];
    }
}
