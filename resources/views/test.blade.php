<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GeChat用户登录</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <!-- load css -->
    <link rel="stylesheet" type="text/css" href="{{URL::asset('layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('geekclouds/css/login.css')}}" media="all">
</head>
<body>
<div class="layui-canvs"></div>
<div class="layui-layout layui-layout-login">
    <h1>
        <strong>GeChat用户登陆</strong>
        <em>Management System</em>
    </h1>
    <form role="form" action="{{URL::asset('gechat/test')}}" method="post" class="registration-form">
        <fieldset>
            {{ csrf_field() }}
            <div class="layui-user-icon larry-login">
                <input type="text" placeholder="账号" class="login_txtbx"/>
            </div>
            <div class="layui-pwd-icon larry-login">
                <input type="password" placeholder="密码" class="login_txtbx"/>
            </div>
            <div class="layui-val-icon larry-login">
                <div class="layui-code-box">
                    <input type="text" id="code" name="code" placeholder="验证码" maxlength="4" class="login_txtbx">
                    <img src="images/verifyimg.png" alt="" class="verifyImg" id="verifyImg" onClick="javascript:this.src='xxx'+Math.random();">
                </div>
            </div>
            <div class="layui-submit larry-login">
                <input type="submit" value="立即登陆" class="submit_btn"/>
            </div>
        </fieldset>
    </form>
    <div class="layui-login-text">
        <p>© 2017-2018 GeChat 版权所有</p>
        <p>吉xxxxxx</p>
    </div>
    <div>

    </div>
</div>
<script src="{{URL::asset('js/jquery-2.1.4.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('layui/layui.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('geekclouds/js/login.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('geekclouds/jsplug/jparticle.jquery.js')}}"></script>
<script type="text/javascript">
    $(function(){
        $(".layui-canvs").jParticle({
            background: "#141414",
            color: "#E6E6E6"
        });
    });
</script>
</body>
</html>