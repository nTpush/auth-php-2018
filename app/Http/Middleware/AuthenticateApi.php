<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


class AuthenticateApi
{

    public function handle($request, Closure $next, $guard=null )
    {

        $whiteList = [ '/api/user/login', '/api/system/menu' ];
        $path = parse_url($request->url())['path'];
        if(!in_array($path, $whiteList)) {
            if (!$request->header('authorization'))
                return response()->json([
                    'code' => false,
                    'data' => [],
                    'message' => 'token不存在',
                    'tag' => null
                ]);
            else
                return $next($request);
        }

        return $next($request);
    }
}
