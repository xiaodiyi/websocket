<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>GeChat-v2.0 - 注册</title>

    <link rel="stylesheet" href="{{URL::asset('layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('org/city-picker/city-picker.css')}}" media="all">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('css/profile.css')}}" media="all">
</head>
<style>
	.gechat-label{
		width: 110px;
		padding: 8px 15px;
		height: 38px;
		line-height: 20px;
		border-width: 1px;
		border-style: solid;
		border-radius: 2px 0 0 2px;
		text-align: center;
		background-color: #FBFBFB;
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
		box-sizing: border-box;
	}
</style>
<body style="width: 100%!important;">
<div class="main layui-clear" style="width:100%;margin:0 auto;box-shadow: 2px 10px 15px 8px #888888;padding: 30px;">
    <blockquote class="layui-elem-quote">
        GeChat-v2.0 注册 账号
    </blockquote>
    <div class="layui-form layui-form-pane">
        <form method="post" id="register_form">
            {{csrf_field()}}
            <br/>
            <h2 class="page-title layui-elem-quote layui-quote-nm">账号信息</h2>
            <hr/>
            <div class="layui-form-item">
                <label for="form-username" class="layui-form-label">昵称</label>
                <div class="layui-input-inline">
                    <input type="text" id="form-username" name="form-username" lay-verify="username" autocomplete="off"
                           class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">必填，注册完会自动弹出您的数字账号！</div>
            </div>
            <div class="layui-form-item">
                <label for="form-pwd" class="layui-form-label">密码</label>
                <div class="layui-input-inline">
                    <input type="password" id="form-pwd" name="form-pwd" lay-verify="pass" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">必填，6到16个字符</div>
            </div>
            <div class="layui-form-item">
                <label for="form-repwd" class="layui-form-label">重复密码</label>
                <div class="layui-input-inline">
                    <input type="password" id="form-repwd" name="form-repwd" lay-verify="pass" autocomplete="off"
                           class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">必填，6到16个字符</div>
            </div>

            <br/>
            <h2 class="page-title layui-elem-quote layui-quote-nm">个人信息</h2>
            <div class="layui-form-item">
                <label for="form-sex" class="layui-form-label">性别</label>
                <div class="layui-input-block">
                    <input name="form-sex" value="1" title="男" type="radio">
                    <input name="form-sex" value="-1" title="女" type="radio">
                    <input name="form-sex" value="0" title="保密" type="radio">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label gechat-label" style="float: left">居住地区</label>
                <div style="position: relative;float: left" class="city-selector"><!-- container -->
                    <input name="form-district" readonly type="text" data-toggle="city-picker" data-responsive="true"  data-simple="true" size="50" value="{{$local[0]}}/{{$local[1]}}/{{$local[2]}}">
                </div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn" lay-filter="*" lay-submit>立即注册</button>
            </div>
            <div class="layui-form-item fly-form-app">
                注：所在区域不选择,系统默认为根据ip定位的所在地,只精确到市。<br/>
                若无法定位，则默认选中 北京-北京市-东城区
            </div>
        </form>
    </div>
</div>

<script src="{{URL::asset('js/jquery-3.2.1.min.js')}}"></script>
<script src="{{URL::asset('layui/layui.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('org/city-picker/city-picker.data.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('org/city-picker/city-picker.js')}}"></script>
<script type="text/javascript">
    layui.use(['form','layer'], function(){
        var form = layui.form
            ,layer = layui.layer;
        @if(session('success'))
            parent.location.reload();
        @endif
        //监听提交
        form.on('submit(formDemo)', function(data){
            layer.msg(JSON.stringify(data.field));
            return false;
        });
        $('#city-picker2').citypicker({});
        @if ($errors->any())
        @foreach ($errors->all() as $error)
        layer.msg("{{ $error }}");
        @break
        @endforeach
        @endif

        @if(session('error'))
        layer.msg("{{session('error')}}");
        @endif
    });
</script>
</body>
</html>