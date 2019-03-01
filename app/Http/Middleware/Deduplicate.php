<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class Deduplicate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next) //防止网络延迟的重复提交
    {
        if(Session::has('local') && session('lock') == true) {
            $result = array(
                'code' => 9,
                'status' => "访问频率过高",
                'data' => ""
            );

            return response(json_encode($result, JSON_UNESCAPED_UNICODE), 200);
        }
        session(['local' => true]);

        if( Session::has('time') && session('time') + 10 > time() ) {
            $result = array(
                'code' => 10,
                'status' => "重复提交，请十秒后重试",
                'data' => ""
            );

            return response(json_encode($result, JSON_UNESCAPED_UNICODE), 200);
        }

        $response = $next($request);

        session(['lock' => false]);

        return $response;
    }
}
