<?php
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/7/23
 * Time: 10:03
 */

namespace App\Http\Services\Node;

use App\Http\Services\Service;
use App\Models\Node\BaseNodeUser;

class LoginServices extends Service
{
    protected $user;

    public function __construct()
    {
        $this->user = new BaseNodeUser();
    }

    public function create($request) {
        return $this->user->NodeUserCreate($request);
    }

    public function userInfo($user) {
        return $this->user->NodeUserInfo($user)->makeVisible('password');
    }

    public function userList() {
        $lists = $this->user->NodeUserList();

//        $res = [];
//        foreach ($lists as $k => $v) {
//            $c = collect($v)->except('password');
//            $res[$k] = $c;
//        }
//
//        $lists['data'] = $res;


        return $lists;
    }
}