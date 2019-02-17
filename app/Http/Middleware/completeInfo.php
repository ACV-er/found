<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class completeInfo
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
            return $this->msg(3, __LINE__);
        }

        $info = User::query()->where('id', session('id'))->first();
        if($info->qq == null && $info->wx == null && $info->pnone == null) {
            return $this->msg(4, __LINE__);
        }

        return $next($request);
    }
}
