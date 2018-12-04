<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NodeUserRequest extends FormRequest
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
            'user' => 'required|min:6|unique:base_node_user',
            'password' => 'required|min:6',
            'password_confirm' => 'required|same:password'
        ];
    }

    public function messages()
    {
        return [
            "user.required"             =>     '请输入用户名',
            "user.min"                  =>     '用户太短啦，请输入大于6位字符的用户名',
            "user.unique"                  =>     '用户名已存在，请换一个呗',
            "password.required"                  =>     '请输入密码',
            "password.min"                  =>     '密码太短啦，请输入大于6位字符的密码',
            "password_confirm.required"                  =>     '请再输一遍密码',
            "password_confirm.same"                  =>     '重复密码输入错误，请重新输入',
        ];
    }
}
