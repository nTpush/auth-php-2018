<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPostRequest extends FormRequest
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
            'name' => 'required|unique:users',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6',
            'password_confirm' => 'required|same:password'
        ];
    }

    public function messages()
    {
        return [
            "name.required"                 =>     '请输入name',
            "email.required"                =>     '请输入email',
            "password.required"             =>     '请输入password',
            "password_confirm.required"     =>     '请输入password_confirm',
            "name.unique"                   =>     'name已存在',
            "email.unique"                  =>     'email已存在',
            "email.email"                   =>     '请输入正确的email格式',
            "password.min"                  =>     '密码最小6位数',
            "password_confirm.same"         =>     '重复密码输入不正确',
        ];
    }
}
