<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordPostRequest extends FormRequest
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
            'password' => 'required|min:6',
            'password_confirm' => 'required|same:password'
        ];
    }

    public function messages()
    {
        return [
            "password.required"             =>     '请输入password',
            "password_confirm.required"     =>     '请输入password_confirm',
            "password.min"                  =>     '密码最小6位数',
            "password_confirm.same"         =>     '重复密码输入不正确',
        ];
    }
}
