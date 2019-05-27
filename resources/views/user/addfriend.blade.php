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
            <form class="layui-form layui-form-pane" action="{{URL::asset('gechat/chatuser/applyfriend')}}" method="post">
                {{csrf_field()}}
                <input type="hidden" name="form-target" value="{{$friend->id}}">
                <div class="layui-form-item">
                    <div style="float: left;width: 30%;text-align: center">
                        <img src="{{$friend->avatar}}" width="100px" height="100px"style="margin-top: 20px;"/>
                        <h5 style="margin-top: 10px">{{$friend->user_name}}</h5>
                    </div>
                    <div style="float: left;width: 65% ;padding: 10px 0 10px 10px;" >
                        <select name="form-group" lay-verify="">
                            @if (!empty($group))
                                @foreach($group as $v)
                                    <option value="{{$v->group_id}}">{{$v->group_name}}</option>
                                @endforeach
                            @endif
                        </select>
                        <textarea name="form-remark" required lay-verify="required" placeholder="验证信息..." class="layui-textarea" style="margin-top: 10px"></textarea>
                    </div>
                </div>
                <div class="layui-form-item" style="float: right">
                    <button class="layui-btn" lay-submit id="btn_submit">提交申请</button>
                    <a  href="javascript:window.opener=null;window.close();" class="layui-btn layui-btn-normal" id="btn_close">返回</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{{URL::asset('js/jquery-3.2.1.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('layui/layui.js')}}"></script>
<script type="text/javascript">
    layui.use(['form'], function(){
        var form = layui.form
            ,layer= layui.layer;
        @if(session('status'))
            layer.msg('{{session('status')}}');
        @endif
        //监听提交
        form.on('submit(formDemo)', function(data){
            layer.msg(JSON.stringify(data.field));
            return true;
        });
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