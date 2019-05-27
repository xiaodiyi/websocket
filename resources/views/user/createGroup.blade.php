<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>laychat-v3.0 - 创建群组</title>
    <link rel="stylesheet" type="text/css" href="{{URL::asset('layui/css/layui.css')}}" media="all">
</head>
<body>
<div class="main layui-clear" style="margin-top:15px">
    <div class="layui-form layui-form-pane" style="width: 70%;margin: 0 auto">
        <form class="form-horizontal m-t layui-form" id="userForm" method="post" action="{{URL::asset('gechat/chatuser/createGroup')}}">
            {{csrf_field()}}
            <div class="layui-form-item">
                <label for="group_name" class="layui-form-label">群名称</label>
                <div class="layui-input-block">
                    <input type="text" id="group_name" name="form-group-name" class="layui-input" >
                </div>
            </div>
            <input type="hidden" id="group_avatar" name="form-group-avatar"/>
            <div class="layui-form-item">
                <div class="layui-input-block" style="margin-left: 0">
                    <img id="ge_group_avatar" src="{{URL::asset('images/avatar.png')}}" width="113px" height="110px">
                    <div class="site-demo-upbar">
                        <button type="button" class="layui-btn" id="ge_group_upload">
                            <i class="layui-icon">&#xe67c;</i>修改头像
                        </button>
                    </div>
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">群介绍</label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" name="form-group-profile" class="layui-textarea"></textarea>
                </div>
            </div>
            <div class="layui-form-item" style="float:right">
                <button class="layui-btn" type="submit" id="sub">提交</button>
            </div>
        </form>
        <div class="layui-form-item fly-form-app">
            注：上传的图片只能是png、jpg，且大小为521kb以内<br/>
        </div>
    </div>
</div>
<script src="{{URL::asset('js/jquery-3.2.1.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('layui/layui.js')}}"></script>
<script type="text/javascript">
    var add_group_url = "{{URL::asset('gechat/chatuser/createGroup')}}";
    var up_avatar_url = "{{URL::asset('gechat/chatuser/upavatar')}}";
    var gechat_url_init ="{{URL::asset('gechat/chatuser/init')}}";
    layui.use(['form','layer'], function() {
        var form = layui.form;
        var layer = layui.layer;
        @if ($errors->any())
        @foreach ($errors->all() as $error)
        layer.msg("{{ $error }}", {icon: 5});
        @break
        @endforeach
        @endif
        @if(session('status'))
                @if(session('status') == 'success')
                $.post(gechat_url_init,{
                    type: 'addGroup',
                    join_id : '{{session('GEEK')}}' ,
                    avatar : '{{session('avatar')}}' ,
                    id: '{{session('group_id')}}',
                    groupname: '{{session('group_name')}}' ,
                    _token : '{{csrf_token()}}'
                }, function (res){
                },'json');
                layer.msg('恭喜，群组：{{session('group_name')}} 创建成功！', {icon: 6});
                setTimeout(function (){
                    //当你在iframe页面关闭自身时
                    var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                    parent.layer.close(index); //再执行关闭
                },2000);
                {{--
                    layer.open({
                        title: '请收下您的群号',
                        area: '300px'
                        ,content: '<div style="padding: 50px; line-height: 22px; background-color: #393D49; color: #fff; font-weight: 300;">群组创建成功，请记下您的群号：<br>GeChat-v4.2<br><br>虽然"前无古人"，但我还是努力去实现GeChat的"聊天梦"<br><br>您的群号 ：<span style = color:#1EFFFF;>{{session('group_num')}}</span> ，群名称 ：<span style = "color:rgb(138, 43, 226);">{{session('group_name')}}</span><br><br>让我们携手彼此,开启Web聊天新时代 ^_^</div>'
                    });
                --}}
            @else
                layer.msg('{{session('status')}}', {icon: 6});
            @endif
        @endif
    });
    layui.use(['upload'], function(){
        var upload = layui.upload;
        //执行实例
        var uploadInst = upload.render({
            elem: '#ge_group_upload' //绑定元素
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
                        $("#ge_group_avatar").attr('src',val);
                        $("#group_avatar").val(val);
                        return false;
                    }
                });

                //$("#user_avatar").val(res[data][src]);
            }
            ,error: function(){
                //请求异常回调
            }
        });
    });
</script>
</body>
</html>