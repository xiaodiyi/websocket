<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GeChat-v5.0 - 举报</title>
    <link rel="stylesheet" type="text/css" href="{{URL::asset('layui/css/layui.css')}}" media="all">
    <style>
        .tab_hover{background:#1E9FFF;color:white}
        .layui-form-pane blockquote{cursor: pointer}
    </style>
</head>
<body>
<div class="main layui-clear" style="margin-top:15px;padding: 10px">
    @if($type == 'friend')
        <blockquote class="layui-elem-quote">
            确定举报好友 <span style="color: red;">{{$friendInfo->user_name}}</span> ?
        </blockquote>
    @else
        <blockquote class="layui-elem-quote">
            确定举报群 <span style="color: red;">{{$groupInfo->group_name}}</span> ?
        </blockquote>
    @endif
    <!-- detail -->
        <div class="layui-form layui-form-pane" style="margin: 0 auto;" id="detail">
            <div class="layui-field-box">
                请您填写举报原因：
            </div>
        </div>
    <textarea id="demo" style="display: none;"></textarea>
    <!-- final -->
    <div class="layui-form layui-form-pane" style="margin: 0 auto;" id="final">
        <div class="layui-field-box">
            {{--我们还会将您于  {$user.user_name}  的最近 5条 聊天记录作为证据一并提交--}}
        </div>
        <button class="layui-btn layui-btn-small" id="report" style="float:right;margin-top: 10px">确定提交</button>
    </div>
</div>
<script type="text/javascript" src="{{URL::asset('layui/layui.js')}}"></script>
<script>
    var upload_img_url = "{{URL::asset('gechat/upload/uploadImg')}}";
    @if($type == 'friend')
        var report_friend_url = "{{URL::asset('gechat/chatuser/reportFriend'.'/'.$friendInfo->id)}}";
    @else
        var report_group_url = "{{URL::asset('gechat/chatuser/reportGroup'.'/'.$groupInfo->id)}}";
    @endif


    layui.use(['layedit','jquery'], function(){
        var $ = layui.jquery
            ,layer = layui.layer;
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        var layedit = layui.layedit;
        var index = layedit.build('demo', {//设置编辑器高度
            uploadImage:{
                url: upload_img_url //接口url
                ,type: 'post' //默认post
            }
        }); //建立编辑器
        $("#report").click(function(){
            @if($type == 'friend')
                $.post(report_friend_url,{
                    content: layedit.getContent(index)
                    ,type  : 'friend'
                } ,function (res) {
                    var data = JSON.parse(res);
                    if (data.code == 0){
                        layer.msg(data.msg, {icon: 6});
                        setTimeout(function () {
                            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                            parent.layer.close(index); //再执行关闭
                        },2000);

                    }
                    else{
                        layer.msg(data.msg,{icon:5});
                    }
                });
            @else
                $.post(report_group_url,{
                    content: layedit.getContent(index)
                    ,type  : 'group'
                } ,function (res) {
                    var data = JSON.parse(res);
                    if (data.code == 0){
                        layer.msg(data.msg, {icon: 6});
                        setTimeout(function () {
                            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                            parent.layer.close(index); //再执行关闭
                        },2500);

                    }
                    else{
                        layer.msg(data.msg,{icon:5});
                    }
                });
            @endif
        });
    });
</script>
</body>
</html>