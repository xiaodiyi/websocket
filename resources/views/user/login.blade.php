<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>极客聊天-用户登录</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <!-- load css -->
    <link rel="stylesheet" type="text/css" href="{{URL::asset('layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('css/login.css')}}" media="all">
</head>
<body>
<div class="layui-canvs"></div>
<div class="layui-layout layui-layout-login">
    <h1>
        <strong>GeChat用户登陆</strong>
        <em>Experience Version</em>
    </h1>
    <form role="form" action="{{URL::asset('gechat/login')}}" method="post" class="registration-form">
        <fieldset>
            {{ csrf_field() }}
                <div class="layui-user-icon larry-login">
                    <input name="form-user" type="text" placeholder="账号" class="login_txtbx"/>
                </div>
                <div class="layui-pwd-icon larry-login">
                    <input name="form-pwd" type="password" placeholder="密码" class="login_txtbx"/>
                </div>
                {{--<div class="layui-val-icon larry-login">
                    <div class="layui-code-box">
                        <input type="text" name="captcha" placeholder="验证码..." class="form-email form-control modal-form" id="captcha" onfocus="showCode()" onblur="checkAuthCode()" >
                        --<script language="javascript" type="text/javascript">
                            function showCode(){
                                var windowWidth = $(window).width();
                                if(windowWidth < 640){
                                    $("#showcode").show("blind",0,1000);
                                }
                                if(windowWidth >= 640){
                                    $("#showcode").show("blind",0,1000);
                                }
                            }
                        </script>
                        <span id='showcode' style='display:none;'><img src="{!! captcha_src('flat')!!}" onclick="this.src='{!! captcha_src('flat')!!}'+Math.random()"></span>

                                </div>
                </div>--}}
                <div class="layui-submit larry-login">
                    <input type="submit" value="立即登陆" class="submit_btn"/>
                </div>
                <div class="layui-submit larry-login">
                    <input type="button" id="register" value="点我注册"  class="submit_btn"/>
                </div>
        </fieldset>
    </form>
    <div class="layui-login-text">
        <p>© 2018-2019 GeChat 版权所有</p>
        <p><a href="http://www.geekadpt.cn" target="_blank">极客凌云旗下-'赋'字号</a></p>
    </div>
</div>
<script src="{{URL::asset('js/jquery-3.2.1.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('layui/layui.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('geekclouds/js/login.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('geekclouds/jsplug/jparticle.jquery.js')}}"></script>
<script type="text/javascript">
    var gechat_register_url = '{{URL::asset('gechat/register')}}';
    layui.use('layer', function(){
        var layer = layui.layer
            ,$ = layui.jquery;
        @if ($errors->any())
        @foreach ($errors->all() as $error)
        layer.msg("{{ $error }}");
        @break
        @endforeach
        @endif

        @if(session('error'))
        layer.msg("{{session('error')}}");
        @endif
        @if(session('success'))
            layer.msg("{{session('success')}}");
        @endif
        @if(session('GeChatNumber'))
            layer.open({
                title: '请牢记您的账号',
                area: '500px'
                ,content: '<div style="padding: 50px; line-height: 22px; background-color: #393D49; color: #fff; font-weight: 300;">你知道吗？亲！<br><br>极云赋-v2.0是基于layim和Laravel的一款独立的即时通讯系统，由于其便捷的特点，还可以被用作网站的客服系统<br><br>虽然"前无古人"，但我还是努力去实现GeChat的"聊天梦"<br><br>你 ：<span style = color:#1EFFFF;>{{session('GeChatNumber')}}</span> ，我 ：<span style = "color:rgb(138, 43, 226);">88888888</span><br><br>让我们携手彼此,开启Web聊天新时代 ^_^</div>'
            });
        @endif
        $('#register').click(function () {
            layer.open({
                type: 2,
                title: 'GeChat-注册',
                maxmin: false, //开启最大化最小化按钮
                area: ['800px', '600px'],
                content: gechat_register_url
            });
        })
    });
    $(function(){
        $(".layui-canvs").jParticle({
            background: "#141414",
            color: "#E6E6E6"
        });
    });
</script>
</body>
</html>