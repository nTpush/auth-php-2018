<?php

namespace App\Http\Middleware;

use App\Models\BaseOperationLog;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class AdminOperationLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $user_id = 0;


        if(Auth::check()) {
            $user_id = (int) Auth::id();

            // --------- 当前账号有且只存在1个
            $name = DB::table('users')->where('id', $user_id)->first()->name;

            $token = Redis::get($name);

            $res = $request->header('Authorization');

            if($res != 'Bearer ' . $token) {
                return response()->json([
                    'code' => false,
                    'data' => [],
                    'message' => '该账号已在异地登录，请重新登录',
                    'tag' => null
                ]);
            }else{
                return $next($request);
            }
        }

        $_SERVER['admin_uid'] = $user_id;
        if('GET' != $request->method()){
            $input = $request->all();

            $log = new BaseOperationLog(); # 提前创建表、model
            $log->user_id = $user_id;
            $log->path = $request->path();
            $log->method = $request->method();
            $log->ip = $request->ip();
            $log->sql = '';
            $log->input = json_encode($input, JSON_UNESCAPED_UNICODE);
            $log->save();  # 记录日志
        }




        return $next($request);
    }
}
