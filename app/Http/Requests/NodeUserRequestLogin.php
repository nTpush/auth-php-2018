<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NodeUserRequestLogin extends FormRequest
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
            'user' => 'required',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            "user.required"             =>     '请输入用户名',
            "password.required"                  =>     '请输入密码',
        ];
    }
}
