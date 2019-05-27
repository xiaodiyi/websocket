<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title></title>
    <link rel="stylesheet" type="text/css" href="{{URL::asset('layui/css/layui.css')}}" media="all">
</head>
<body>
<div class="main layui-clear" style="padding: 15px">
    <form class="layui-form layui-form-pane" action="{{URL::asset('gechat/chatuser/dochange')}}" method="post">
        {{csrf_field()}}
        <div class="layui-form-item">
            <label for="user_name" class="layui-form-label">昵称</label>
            <div class="layui-input-inline">
                <input type="text" id="user_name" disabled name="user_name" autocomplete="off" class="layui-input" value="{{$friendInfo->user_name}}">
            </div>
            <div class="" style="position: absolute;right: 5px;top: 5px;">
                <img id="ge_avatar_upload" class="layui-circle" src="{{$friendInfo->avatar}}" width="110px" height="110px">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="user_name" class="layui-form-label">账号</label>
            <div class="layui-input-inline">
                <input type="text" id="user_name" disabled name="user_name" autocomplete="off" class="layui-input" value="{{$friendInfo->number}}">
            </div>
        </div>
        <blockquote class="layui-elem-quote">
            基本资料
        </blockquote>
        <div class="layui-form-item">
            <label for="user_name" class="layui-form-label">年龄</label>
            <div class="layui-input-inline">
                <input type="text" id="user_name" disabled name="user_name" autocomplete="off" class="layui-input" value="{{$friendInfo->age == -1? '保密' : $friendInfo->age}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">性别</label>
            <div class="layui-input-block">
                <input type="checkbox" name="like[write]" @if($friendInfo -> sex == 1)checked @else disabled @endif title="男">
                <input type="checkbox" name="like[read]" @if($friendInfo -> sex == -1)checked @else disabled @endif  title="女">
                <input type="checkbox" name="like[dai]" @if($friendInfo -> sex == 0)checked @else disabled @endif  title="保密">
            </div>
        </div>
        <div class="layui-form-item" >
            <label for="user_name" class="layui-form-label">生日</label>
            <div class="layui-input-inline" >
                <input type="text" id="user_name" disabled name="user_name" autocomplete="off" class="layui-input" value="{{$friendInfo->birth == ''? '保密' : $friendInfo->birth}}">
            </div>
        </div>
        <blockquote class="layui-elem-quote">
            其他信息
        </blockquote>
        <div class="layui-form-item">
            <label for="user_name" class="layui-form-label">居住地区</label>
            <div class="layui-input-inline" style="width:60%">
                <input type="text" id="user_name" disabled name="user_name" autocomplete="off" class="layui-input" value="{{$friendInfo->area}}">
            </div>
        </div>
        <div class="layui-form-item layui-col-md6">
                <label for="user_name" class="layui-form-label">血型</label>
                <div class="layui-input-inline" >
                    <input type="text" id="user_name" disabled name="user_name" autocomplete="off" class="layui-input" value="{{$friendInfo->blood_type}}">
                </div>
        </div>
        <div class="layui-form-item layui-col-md6" >
                <label for="user_name" class="layui-form-label">星座</label>
                <div class="layui-input-inline" >
                    <input type="text" id="user_name" disabled name="user_name" autocomplete="off" class="layui-input" value="{{$friendInfo->constellation}}">
                </div>
        </div>
        <div class="layui-form-item layui-col-md6" >
            <label for="user_name" class="layui-form-label">QQ</label>
            <div class="layui-input-inline" >
                <input type="text" id="user_name" disabled name="user_name" autocomplete="off" class="layui-input" value="{{$friendInfo->QQ}}">
            </div>
        </div>
        <div class="layui-form-item layui-col-md6" >
            <label for="user_name" class="layui-form-label">Email</label>
            <div class="layui-input-inline" >
                <input type="text" id="user_name" disabled name="user_name" autocomplete="off" class="layui-input" value="暂未开放">
            </div>
        </div>
        <div class="layui-form-item layui-col-md6" >
            <label for="user_name" class="layui-form-label">手机号</label>
            <div class="layui-input-inline" >
                <input type="text" id="user_name" disabled name="user_name" autocomplete="off" class="layui-input" value="{{$friendInfo->tel}}">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">个性签名</label>
            <div class="layui-input-block">
                <textarea disabled name="desc" placeholder="{{$friendInfo->sign}}" class="layui-textarea"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="formDemo">聊天</button>
                <button type="reset" class="layui-btn layui-btn-primary" id="closeBtn-friendInfo">关闭</button>
            </div>
        </div>

    </form>
</div>
<script src="{{URL::asset('js/jquery-3.2.1.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('layui/layui.js')}}"></script>
<script type="text/javascript">
    //Demo
    layui.use('form', function(){
        var form = layui.form
            ,layer = layui.layer;
        $("#closeBtn-friendInfo").click(function () {
            //当你在iframe页面关闭自身时
            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            parent.layer.close(index); //再执行关闭
        });
        //监听提交
        form.on('submit(formDemo)', function(data){
            layer.msg(JSON.stringify(data.field));
            return false;
        });
    });
</script>
</body>
</html>