<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>极云赋 - 修改密码</title>
    <link rel="stylesheet" type="text/css" href="{{URL::asset('layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('css/profile.css')}}" media="all">
</head>
<body>
<div class="main layui-clear" style="padding: 15px">
    <form class="layui-form layui-form-pane" action="{{URL::asset('gechat/chatuser/changePwd')}}" method="post">
        {{csrf_field()}}
            <blockquote class="layui-elem-quote">
                修改密码
            </blockquote>
            <div class="layui-form-item">
                <label for="pwd" class="layui-form-label">旧密码</label>
                <div class="layui-input-inline">
                    <input type="password" id="oldpwd" name="oldpwd" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="pwd" class="layui-form-label" >新密码</label>
                <div class="layui-input-inline">
                    <input type="password" id="pwd" name="pwd" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">6到16个字符</div>
            </div>
            <div class="layui-form-item">
                <label for="repwd" class="layui-form-label" >重复新密码</label>
                <div class="layui-input-inline">
                    <input type="password" id="repwd" name="repwd" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">6到16个字符</div>
            </div>
            <div class="layui-form-item fly-form-app">
                注：密码改后下次登录用
            </div>
            <div class="layui-form-item">
                <button class="layui-btn" id="change_pwd_btn" lay-submit  lay-filter="formDemo">确认提交</button>
            </div>
    </form>
</div>
<script type="text/javascript" src="{{URL::asset('layui/layui.js')}}"></script>
<script type="text/javascript">
    var up_avatar_url = "{{URL::asset('gechat/chatuser/upavatar')}}";
    var change_pwd_url = "{{URL::asset('gechat/chatuser/changePwd')}}";
    //Demo
    layui.use(['form','jquery','layer'], function(){
        var form = layui.form
            ,$ = layui.jquery
            ,layer = layui.layer;
        //监听提交
        form.on('submit(formDemo)', function(data){
            //layer.msg(JSON.stringify(data.field));
            return true;
        });
        @if ($errors->any())
        @foreach ($errors->all() as $error)
            layer.msg("{{ $error }}",{icon:5});
        @break
        @endforeach
        @endif
        @if(session('error'))
            layer.msg("{{session('error')}}",{icon:5});
        @endif
        @if(session('success'))
            layer.msg("{{session('success')}}",{icon:6});
        @endif
    });

</script>
</body>
</html>