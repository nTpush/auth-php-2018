<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CheckNode
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
        $user = Auth::user();
        $path = $request->path();
        $method = $request->method();

       $arr = explode('/', $path);

       // 判断带参数的git请求
        if(count($arr) == 4 && $arr[3] != 'create') {
            array_pop($arr);
            $path = implode('/', $arr) . '/id';
        }


        $query = '/' . $path . '@' . strtolower($method);


        $nodeId = DB::table('base_resource_node')->select('node_id')->where('node_url', $query)->first()->node_id;
        $roles = json_decode(Redis::get($user->id));
        $nodes = $roles->node;



        if(in_array($nodeId, $nodes)) {
            return $next($request);
        }else {
            return response()->json([
                'code' => false,
                'data' => [],
                'message' => '对不起，您没有权限访问该接口！',
                'tag' => null
            ]);
        }
    }
}
