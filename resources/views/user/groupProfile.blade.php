<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title></title>
    <link rel="stylesheet" type="text/css" href="{{URL::asset('layui/css/layui.css')}}" media="all">
</head>
<style>
    body .layui-layim-members{
        margin: 0!important;
    }
    .layui-layim-members ul{
        width: auto !important;
    }
    .layui-layim-members ul{
        border: none!important;
    }
    .layui-box, .layui-box * {
        box-sizing: content-box;
    }
    .gechat-ucenter-collapse{
        border-color: rgb(239, 242, 247) rgb(239, 242, 247) rgb(239, 242, 247) rgb(34, 153, 238);
        border-left: 5px solid rgb(34, 153, 238);
        margin: 0!important;
    }
</style>
<body>
<div class="main layui-clear" style="padding: 15px">
    <form class="layui-form layui-form-pane" action="{{URL::asset('gechat/chatuser/dochange')}}" method="post">
        {{csrf_field()}}
        <blockquote class="layui-elem-quote">
            基本资料
        </blockquote>
        <div class="layui-form-item">
            <label for="user_name" class="layui-form-label">群名称</label>
            <div class="layui-input-inline">
                <input type="text" id="user_name" disabled name="user_name" autocomplete="off" class="layui-input" value="{{$groupInfo->group_name}}">
            </div>
            <div class="layui-circle" style="position: absolute;right: 15px;top: 68px;">
                <img id="ge_avatar_upload" class="layui-circle" src="{{$groupInfo->group_avatar}}" width="110px" height="110px">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="user_name" class="layui-form-label">群号</label>
            <div class="layui-input-inline">
                <input type="text" id="user_name" disabled name="user_name" autocomplete="off" class="layui-input" value="{{$groupInfo->group_num}}">
            </div>
        </div>
        {{--
            <div class="layui-form-item">
                <label for="user_name" class="layui-form-label">群主</label>
                <div class="layui-input-inline">
                    <input type="text" id="" disabled name="" autocomplete="off" class="layui-input" value="{{$groupMembers['owner']->user_name}}">
                </div>
            </div>
        --}}
        <div class="layui-form-item">
            <div class="layui-collapse ">
                <div class="layui-colla-item ">
                    <h2 class="layui-colla-title gechat-ucenter-collapse">群成员</h2>
                    <div class="layui-colla-content layui-show">
                        <div class=" layui-box layui-layim-members">
                                    <li style="padding-left: 10px" data-uid="{{$groupMembers['owner']->id}}"><a href="javascript:;"><img src="{{$groupMembers['owner']->avatar}}"><cite>{{$groupMembers['owner']->user_name}}</cite></a></li>
                            @if(!empty($groupMembers['members']))
                                <ul class="layim-members-list">
                                @foreach($groupMembers['members'] as $key => $v)
                                        <li data-uid="{{$v->id}}"><a href="javascript:;"><img src="{{$v->avatar}}"><cite>{{$v->user_name}}</cite></a></li>
                                @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">群简介</label>
            <div class="layui-input-block">
                <textarea disabled name="desc" placeholder="{{$groupInfo->group_profile}}" class="layui-textarea"></textarea>
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
    layui.use(['form','layim','element'], function(){
        var form = layui.form
            ,element = layui.element
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