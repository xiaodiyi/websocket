<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>laychat-v3.0 - 修改个人资料</title>
    <link rel="stylesheet" type="text/css" href="{{URL::asset('layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('org/city-picker/city-picker.css')}}" media="all">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('css/profile.css')}}" media="all">
    <!-- 引入 Bootstrap -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div class="main layui-clear" style="padding: 15px">
    <form class="layui-form layui-form-pane" action="{{URL::asset('gechat/chatuser/dochange')}}" method="post">
        {{csrf_field()}}
        <div id="user_info">
            <blockquote class="layui-elem-quote">
                账号信息
            </blockquote>
            <div class="layui-form-item" style="margin-top: 25px">
                <label for="user_name" class="layui-form-label">昵称</label>
                <div class="layui-input-inline">
                    <input type="text" id="user_name" name="user_name" autocomplete="off"
                           class="layui-input" lay-verify="required|username" value="{{old('user_name')?old('user_name'):$uinfo->user_name}}">
                </div>
                <input type="hidden" id="user_avatar" name="user_avatar" value="{{old('user_avatar')?old('user_avatar'):$uinfo->avatar}}"/>
                <div class="" style="position: absolute;right: 15px;top: 68px;">
                    <img id="ge_avatar_upload" src="{{old('user_avatar')?old('user_avatar'):$uinfo->avatar}}" width="113px" height="110px">
                    <div class="site-demo-upbar">
                        <button type="button" class="layui-btn" id="test1">
                            <i class="layui-icon">&#xe67c;</i>修改头像
                        </button>
                    </div>
                </div>
            </div>
            <div class="layui-form-item" style="margin-bottom: 25px">
                <label for="user_name" class="layui-form-label">账号</label>
                <div class="layui-input-inline">
                    <input type="text" id="user_name" disabled name="user_name" autocomplete="off" class="layui-input" value="{{$uinfo->number}}">
                </div>
            </div>
            <blockquote class="layui-elem-quote">
                个人信息
            </blockquote>
            <div class="layui-form-item">
                <label for="repwd" class="layui-form-label">性别</label>
                <div class="layui-input-block">
                    <input name="sex" value="1" title="男" @if($uinfo->sex == 1) checked @endif type="radio">
                    <input name="sex" value="-1" title="女" @if($uinfo->sex == -1) checked @endif type="radio">
                    <input name="sex" value="0" title="保密" @if($uinfo->sex == 0) checked @endif type="radio">
                </div>
            </div>
            <div class="layui-form-item layui-col-md6">
                <label for="repwd" class="layui-form-label">生日</label>
                <div class="layui-input-inline" style="width: 100px">
                    <input type="text" name="form-birth" class="layui-input" id="birth" value="{{old('form-birth')?old('form-birth'):$uinfo->birth}}">
                </div>
                <div class="layui-form-mid layui-word-aux">我们会根据您的生日计算您的年龄.</div>
            </div>
            <div class="layui-form-item layui-col-md6" >
                <label for="user_name" class="layui-form-label">手机号</label>
                <div class="layui-input-inline" >
                    <input type="text" id="" name="form-tel" autocomplete="off" class="layui-input" value="{{old('form-tel')?old('form-tel'):$uinfo->tel}}">
                </div>
            </div>
            <div class="layui-form-item layui-col-md6" >
                <label for="user_name" class="layui-form-label">邮箱</label>
                <div class="layui-input-inline" >
                    <input type="text" id="" disabled name="form-email" autocomplete="off" class="layui-input" value="暂未开放">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label" style="">居住地区</label>
                <div style="position: relative;float: left" class="city-selector"><!-- container -->
                    <input name="district" readonly type="text" data-toggle="city-picker" data-responsive="true"  data-simple="true" size="50" value="{{$local[0]}}/{{$local[1]}}/{{$local[2]}}">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">星座</label>
                <div class="layui-input-block">
                    <select name="form-constellation" lay-verify="required">
                        <option value="保密" @if($uinfo->constellation == '保密') selected @endif>保密</option>
                        <option value="白羊座" @if($uinfo->constellation == '白羊座') selected @endif>白羊座</option>
                        <option value="金牛座" @if($uinfo->constellation == '金牛座') selected @endif>金牛座</option>
                        <option value="双子座" @if($uinfo->constellation == '双子座') selected @endif>双子座</option>
                        <option value="巨蟹座" @if($uinfo->constellation == '巨蟹座') selected @endif>巨蟹座</option>
                        <option value="狮子座" @if($uinfo->constellation == '狮子座') selected @endif>狮子座</option>
                        <option value="处女座" @if($uinfo->constellation == '处女座') selected @endif>处女座</option>
                        <option value="天秤座" @if($uinfo->constellation == '天秤座') selected @endif>天秤座</option>
                        <option value="天蝎座" @if($uinfo->constellation == '天蝎座') selected @endif>天蝎座</option>
                        <option value="射手座" @if($uinfo->constellation == '射手座') selected @endif>射手座</option>
                        <option value="摩羯座" @if($uinfo->constellation == '摩羯座') selected @endif>摩羯座</option>
                        <option value="水瓶座" @if($uinfo->constellation == '水瓶座') selected @endif>水瓶座</option>
                        <option value="双鱼座" @if($uinfo->constellation == '双鱼座') selected @endif>双鱼座</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">血型</label>
                <div class="layui-input-block">
                    <select name="form-blood-type" lay-verify="required">
                        <option value="保密" @if($uinfo->blood_type == '保密') selected @endif>保密</option>
                        <option value="O型" @if($uinfo->blood_type == 'O型') selected @endif>O型</option>
                        <option value="A型" @if($uinfo->blood_type == 'A型') selected @endif>A型</option>
                        <option value="B型" @if($uinfo->blood_type == 'B型') selected @endif> B型</option>
                        <option value="AB型" @if($uinfo->blood_type == 'AB型') selected @endif>AB型</option>
                        <option value="其他" @if($uinfo->blood_type == '其他') selected @endif>其他</option>

                    </select>
                </div>
            </div>
            <div class="layui-form-item layui-col-md6" >
                <label for="user_name" class="layui-form-label">QQ</label>
                <div class="layui-input-inline" >
                    <input type="text" id="" lay-verify="" name="form-QQ" autocomplete="off" class="layui-input" value="{{old('form-QQ')?old('form-QQ'):$uinfo->QQ}}">
                </div>
            </div>
            <div class="layui-form-item layui-col-md6" >
                <label for="user_name" class="layui-form-label">人类验证</label>
                <div class="layui-input-inline" >
                    <input type="text" id="captcha-input"  name="captcha" autocomplete="off" class="layui-input" value="" placeholder="请输入右边的的验证码...">
                </div>
                <span id="captcha-img" style='display:none;'><img src="{!! captcha_src('flat')!!}" onclick="this.src='{!! captcha_src('flat')!!}'+Math.random()" style="float: left;height: 38px"></span>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn" lay-submit id="btn">确认提交</button>
            </div>

        </div>
    </form>
</div>
<script src="{{URL::asset('js/jquery-3.2.1.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('layui/layui.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('org/city-picker/city-picker.data.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('org/city-picker/city-picker.js')}}"></script>
<script type="text/javascript">
    var up_avatar_url = "{{URL::asset('gechat/chatuser/upavatar')}}";
//Demo
    layui.use(['form','layer','upload','laydate'], function(){
        var form = layui.form
            ,upload = layui.upload
            ,layer = layui.layer
            ,laydate = layui.laydate;

        //执行一个laydate实例
        laydate.render({
            elem: '#birth' //指定元素
        });

        $("#captcha-input").click(function () {
           $("#captcha-img").css('display','block');
        });
        var uploadInst = upload.render({
            elem: '#test1' //绑定元素
            ,url: up_avatar_url //上传接口
            ,accept: 'image'
            ,size: 521
            ,data: {
                "_token":"{{csrf_token()}}"
            }
            ,done: function(res){
                console.log(res.data);
                $.each(res.data,function(key,val){
                    if(key == 'src'){
                        console.log('已赋值');
                        $("#ge_avatar_upload").attr('src',val);
                        $("#user_avatar").val(val);
                        return false;
                    }
                });

                //$("#user_avatar").val(res[data][src]);
            }
            ,error: function(){
                //请求异常回调
            }
        });
        form.verify({
            username: function(value, item){ //value：表单的值、item：表单的DOM对象
                if(!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)){
                    return '用户名不能有特殊字符';
                }
                if(/(^\_)|(\__)|(\_+$)/.test(value)){
                    return '用户名首尾不能出现下划线\'_\'';
                }
                if(/^\d+\d+\d$/.test(value)){
                    return '用户名不能全为数字';
                }
            }
            //我们既支持上述函数式的方式，也支持下述数组的形式
            //数组的两个值分别代表：[正则匹配、匹配不符时的提示文字]
            ,pass: [
                /^[\S]{6,12}$/
                ,'密码必须6到12位，且不能出现空格'
            ]
            ,QQ: [
                /^[\S]{6,12}$/
                ,'QQ必须6到12位，且不能出现空格'
            ]
        });
        //监听提交
        form.on('submit(formDemo)', function(data){
            layer.msg(JSON.stringify(data.field));
            return false;
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
            setTimeout(function (){
                var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                parent.layer.close(index); //再执行关闭
            },2000);
        @endif

        console.log('{{$local[0]}}');
        $('#city-picker2').citypicker({});
    });
</script>
</body>
</html>