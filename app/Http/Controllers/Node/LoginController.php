<?php
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/7/23
 * Time: 8:37
 */
namespace App\Http\Controllers\Node;
use App\Http\Controllers\Controller;
use App\Http\Requests\NodeUserRequest;
use App\Http\Requests\NodeUserRequestLogin;
use App\Http\Services\Node\LoginServices;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;
use Zend\Diactoros\Request;

class LoginController extends Controller
{
    protected $userService;

    public function __construct()
    {
        $this->userService = new LoginServices();
    }

    public function regist(NodeUserRequest $request)
    {
        $info = $request->all();
        $info['password'] = bcrypt($info['password']);
        $res = $this->userService->create($info);
        $token = md5($res->user . $res->uuid);
        Redis::set($token, $res);
        // Redis::expire($token, 6000);
        Redis::expire($token, 6000);
        $this->setResponse([
            'token' => $token
        ]);
        return $this->response();
    }

    public function login(NodeUserRequestLogin $request) {
        $username = request()->get('user');
        $password = request()->get('password');
        $user = $this->userService->userInfo($username);
        if(!Hash::check($password, $user['password'])) {
            $this->setResponse([], false, '密码错误');
            return $this->response();
        }
        $token = md5($user->user . $user->uuid);
        Redis::set($token, $user);
        Redis::expire($token, 6000);
        $this->setResponse([
            'token' => $token
        ]);
        return $this->response();
    }

    public function userList() {
        $list = $this->userService->userList();
        $this->setResponse($list);
        return $this->response();
    }
}