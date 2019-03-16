<!doctype html>
<html lang="zh">
    <head>
        <title>失物招领管理端</title>
        <link rel="stylesheet" href="{{ URL::asset('css/manager/login.css') }}">
        <script type="application/javascript" src="{{ URL::asset('js/manager/login.js') }}" ></script>
    </head>
    <body>
        <form id="form" method="POST" action="{{ env('APP_URL').'/manager/login' }}">
            <h1 align="center">登录</h1>
            <label>学号</label>
            <input id="stu_id" type="text" name="stu_id" placeholder="填写学号"/>
            <label>密码</label>
            <input id="password" type="password" name="password" placeholder="教务密码(8位以上)"/>
            <p id="error"></p>
            <input id="_login" type="button" value="登录" onclick="login()"/>
        </form>
    </body>
</html>
