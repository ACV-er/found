<?php

namespace App\Http\Middleware;

use Closure;

class loginCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function msg($code, $msg) {
        $status = array(
            0 => '成功',
            1 => '缺失参数',
            2 => '错误访问',
            3 => '未登录',
            4 => '未完善信息'
        );

        $result = array(
            'code' => $code,
            'status' => $status[$code],
            'data' => $msg
        );

        return json_encode($result,  JSON_UNESCAPED_UNICODE);
    }

    public function handle($request, Closure $next)
    {
        if(session('login') === false) {
            return redirect()->back()->with($this->msg(3, __LINE__));
        }

        return $next($request);
    }
}
