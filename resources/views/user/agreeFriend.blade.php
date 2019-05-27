<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>查找/添加群组</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="{{URL::asset('font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('css/animate.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('css/profile.css')}}" media="all">
</head>
<body class="gray-bg">
<div class="layui-container">
    <div class="layui-row">
        <div class="layui-col-md12">
            <form class="layui-form layui-form-pane" action="{{URL::asset('gechat/chatuser/agreefriend/events')}}" method="post">
                <input type="hidden" name="form-target-friend" id="friendID" value="{{$friend->id}}">
                <input type="hidden" name="form-target-group" id="friendGroupID" value="{{$friendGroupID}}">
                <div class="layui-form-item">
                    <div style="float: left;width: 30%;text-align: center">
                        <img src="{{$friend->avatar}}" width="100px" height="100px"style="margin-top: 20px;"/>
                        <h5 style="margin-top: 10px">{{$friend->user_name}}</h5>
                    </div>
                    <div style="float: left;width: 65% ;padding: 10px 0 10px 10px;margin-top: 10px;" >
                        <select name="form-group" lay-verify="" id="groupID">
                            @if (!empty($group))
                                @foreach($group as $v)
                                    <option value="{{$v->group_id}}">{{$v->group_name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{{URL::asset('js/jquery-3.2.1.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('layui/layui.js')}}"></script>
<script type="text/javascript">
    layui.use(['element','flow','form'], function(){
        var layer = layui.layer
            ,$ = layui.jquery
            ,form = layui.form
        var agree_friend_url = "{{URL::asset('gechat/chatuser/agreeFriends')}}";
        @if(session('status'))
            layer.msg("{{session('status')}}",{icon: 1});
        @endif
        //监听提交
        form.on('submit(formDemo)', function(data){
            layer.msg(JSON.stringify(data.field));
            return true;
        });
        //关闭按钮
        $("#btn_close").click(function () {
            layui.use(['layer'], function(){
                var layer = layui.layer;
                var index=parent.layer.getFrameIndex(window.name);
                parent.layer.close(index);
            });
        });
    });
</script>
</body>
</html>