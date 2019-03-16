<?php

    namespace App\Http\Middleware;

    use Closure;

    class loginCheck
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
                6 => '未登录',
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
            if (session('login') !== true) {
                return response($this->msg(6, __LINE__), 200);
            }

            return $next($request);
        }
    }
