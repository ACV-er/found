<?php

    namespace App\Http\Middleware;

    use Closure;

    class ManagerCheck
    {
        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request $request
         * @param  \Closure $next
         *
         * @return mixed
         */
        public function msg($code, $msg)
        {
            $status = array(
                0 => '成功',
                1 => '缺失参数',
                2 => '错误访问',
                6 => '未登录',
                7 => '未完善信息'
            );

            $result = array(
                'code' => $code,
                'status' => $status[$code],
                'data' => $msg
            );

            return json_encode($result, JSON_UNESCAPED_UNICODE);
        }

        public function handle($request, Closure $next)
        {
            if (session('manager_login') !== true) {
                return response($this->msg(6, __LINE__), 200)->header('Location', env('APP_URL').'/manager/login');
            }

            return $next($request);
        }
    }
