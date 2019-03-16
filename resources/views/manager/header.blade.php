<!doctype html>
<html lang="zh">
<head>
    <title>失物招领管理端</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--<link rel="stylesheet" href="{{ URL::asset('css/manager/home.css') }}">--}}
    {{--<script type="application/javascript" src="{{ URL::asset('js/manager/home.js') }}" ></script>--}}
    <style>
        @yield('activity')

        body {
            min-width: 900px;
            background-color: white;
        }

        #name {
            display: inline-block;
            font-size: 50px;
            text-align: center;
            color: rgb(44, 62, 80);
            margin-left: 5%;
        }

        #top {
            position: relative;
        }

        #navigation {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 450px;
            right: 5%;
            top: 0;
            bottom: 0;
            margin: auto 0;
        }

        a {
            display: inline-block;
            font-size: 25px;
            text-decoration: none;
            color: #11bbfb;
            margin-right: 1px;
            white-space:nowrap;
        }

        li {
            display: inline-block;
            margin-left: 5%;
        }
    </style>
</head>
<body>
<div id="top">
    <div id="name">Hi {{ App\Manager::query()->where('id', session('manager_id'))->first()->name }} </div>
    <ul id="navigation">
        <li><a id="manager" href="{{ env('APP_URL').'/manager/manager' }}">管理员管理</a></li>
        <li><a id="user" href="{{env('APP_URL').'/manager/user'}}">用户管理</a></li>
        <li><a id="post" href="{{ env('APP_URL').'/manager/post'  }}">失物招领管理</a></li>
        <li><a id="other" href="{{ env('APP_URL').'/manager/other'  }}">其他</a></li>
    </ul>
</div>
@yield('body')
</body>
</html>
