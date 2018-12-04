<?php

namespace App\Http\Controllers\Api;

use App\Components\Classes\Helper;
use App\Events\LoginEvent;
use App\Events\PostSaved;

use App\Http\Requests\LoginPostRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Services\Api\LoginService;
use App\User;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\RejectionException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;



class LoginController extends Controller
{
    const FORMPARAMS = [
          'client_id'       => '2',
          'client_secret'   => 'K2qxF7pOOaANwP1jOfNUc50ZV2ZmcjSAxHExImLU',
          'grant_type'      => 'password',
          'scope'           => ''
    ];

    /**
     * 登录
     * @param LoginPostRequest $request
     * @return string
     */
    public function login(LoginPostRequest $request) {

        $username = request()->get('user_name');
        $password = request()->get('user_password');

        $user = User::orWhere('name', $username)->first();

        if($user == null) {
            $this->setResponse([], false, '用户不存在');
            return $this->response();
        }

        if ($user && ($user->status == 0)) {
            $this->setResponse([], false, '账号已被禁用');
            return $this->response();
        }

        if($user && !Hash::check($password, $user->password)) {
            $this->setResponse([], false, '密码错误');
            return $this->response();
        }

        $token = $this->getTokenInfo($username, $password);


        $user_id = $user->id;
        $roles = (new LoginService())->dealRoles($user_id);

        // 登录后将权限信息储存到 redis
        Redis::set($user_id, json_encode($roles));

        $this->setResponse([
            'token' => $token,
            'user' => $user,
            'roles' => $roles
        ]);

        Redis::set($username, $token->access_token);

        $data = [
            'event' => 'UserSignedUp',
            'data' => [
                'username' => $user,
                'token' => $token
            ]
        ];
//        Event::fire(new NodeSocketEvent('shining'));
        Redis::publish('test-channel', json_encode($data));
        event(new LoginEvent($user, new Agent(), $request->getClientIp(), Helper::qgmdate()));
        return $this->response();
    }



    /**
     * 退出
     * @param Request $request
     * @return string
     */
    public function logout(Request $request) {
//         Event::fire(new AccessTokenCreated());
        (Auth::user())->token()->revoke();  // 伪删除
        $user_id = (Auth::user())->id;
        Redis::del($user_id);
//        (Auth::user())->token()->delete(); // 删除记录
         return $this->response();
    }

    /**
     * 验证登录状态
     * @param Request $request
     * @return string
     */
    public function checkLogin(Request $request) {
        $result = Auth::check();
        if($result) {
            $user = Auth::user();
            $user_id = $user->id;

            // 从redist取规则
            $roles = json_decode(Redis::get($user_id));

            $this->setResponse([
                'user' =>$user,
                 'roles' => $roles
            ]);
            return $this->response();
        }
        $this->setResponse([], false, 'token过期');
        return $this->response();
    }

    /**
     * @param Request $request
     * @return string
     */
    public function checkInPage(Request $request) {
        $path = $request->input('path');
        $user = Auth::user();
        $user_id = $user->id;

        $bool = (new LoginService())->checkPage($path, $user_id);

        if($bool)  return $this->response();
        $this->setResponse([], false, '权限不足');
        return $this->response();

    }


    /**
     * 获取token信息
     * @param $username
     * @param $password
     * @return string
     */
    protected function getTokenInfo($username, $password) {
        $client = new Client();
        try {
            $request = $client->request('POST', request()->root() . '/oauth/token', [
                'form_params' => [
                    'grant_type' => self::FORMPARAMS['grant_type'],
                    'client_id' => self::FORMPARAMS['client_id'],
                    'client_secret' => self::FORMPARAMS['client_secret'],
                    'username' => $username,
                    'password' => $password,
                    'scope' => self::FORMPARAMS['scope'],
                ],
            ]);

        } catch (RejectionException $e) {
            throw  new UnauthorizedHttpException('', '账号验证失败');
        }

        if ($request->getStatusCode() == 401) {
            throw  new UnauthorizedHttpException('', '账号验证失败');
        }
        return json_decode($request->getBody()->getContents());
    }

}
