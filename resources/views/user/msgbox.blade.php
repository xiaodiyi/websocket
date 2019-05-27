<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>消息盒子</title>
    <link rel="stylesheet" type="text/css" href="{{URL::asset('layui/css/layui.css')}}" media="all">
    <style>
        .layim-msgbox{margin: 15px;}
        .layim-msgbox li{position: relative; margin-bottom: 10px; padding: 0 130px 10px 60px; padding-bottom: 10px; line-height: 22px; border-bottom: 1px dotted #e2e2e2;}
        .layim-msgbox .layim-msgbox-tips{margin: 0; padding: 10px 0; border: none; text-align: center; color: #999;}
        .layim-msgbox .layim-msgbox-system{padding: 0 10px 10px 10px;}
        .layim-msgbox li p span{padding-left: 5px; color: #999;}
        .layim-msgbox li p em{font-style: normal; color: #FF5722;}
        .layim-msgbox-avatar{position: absolute; left: 0; top: 0; width: 50px; height: 50px;}
        .layim-msgbox-user{padding-top: 5px;}
        .layim-msgbox-content{margin-top: 3px;}
        .layim-msgbox .layui-btn-small{padding: 0 15px; margin-left: 5px;}
        .layim-msgbox-btn{position: absolute; right: 0; top: 12px; color: #999;}
    </style>
</head>
<body>
<a class="btn-refresh" style="display: none;" onclick="javascript:location.replace(location.href);" title="刷新" ></a>
<ul class="layim-msgbox" id="">
@if(!empty($msg))
    @foreach($msg as $v)
        @if($v['type'] == 1)
            <li>
                <a href="javascript:;">
                    <img src="{{$v['user']['avatar']}}" class="layui-circle layim-msgbox-avatar">
                </a>
                <p class="layim-msgbox-user">
                    <a href="javascript:;">{{$v['user']['username']}}</a>
                    <span>{{$v['time']}}</span>
                </p>
                <p class="layim-msgbox-content">
                    {{$v['content']}}
                    <span>{{$v['remark'] != null ? '附言:'.$v['remark'] : ''}}</span>
                </p>
                @if($v['agree'] == 0)
                <p class="layim-msgbox-btn">
                    <button class="layui-btn layui-btn-small" onclick="agree({{$v['user']['id']}})">同意</button>
                    <button class="layui-btn layui-btn-small layui-btn-primary" onclick="refuse({{$v['user']['id']}})">拒绝</button>
                </p>
                @elseif($v['agree'] == 1)
                <p class="layim-msgbox-btn">
                    已同意
                </p>
                @else
                <p class="layim-msgbox-btn">
                    <em>已拒绝</em>
                </p>
                @endif
            </li>
        @elseif($v['type'] == 3)
                <li>
                    <a href="javascript:;">
                        <img src="{{$v['user']['avatar']}}" class="layui-circle layim-msgbox-avatar">
                    </a>
                    <p class="layim-msgbox-user">
                        <a href="javascript:;">{{$v['user']['username']}}</a>
                        <span>{{$v['time']}}</span>
                    </p>
                    <p class="layim-msgbox-content">
                        {{$v['content']}}
                        <span>{{$v['remark'] != null ? '附言:'.$v['remark'] : ''}}</span>
                    </p>
                    @if($v['agree'] == 0)
                        <p class="layim-msgbox-btn">
                            <button class="layui-btn layui-btn-small" onclick="agreeG('{{$v['user']['id']}}','{{$v->from_group}}')">同意</button>
                            <button class="layui-btn layui-btn-small layui-btn-primary" onclick="refuseG('{{$v['user']['id']}}','{{$v->from_group}}')">拒绝</button>
                        </p>
                    @elseif($v['agree'] == 1)
                        <p class="layim-msgbox-btn">
                            已同意
                        </p>
                    @else
                        <p class="layim-msgbox-btn">
                            <em>已拒绝</em>
                        </p>
                    @endif
                </li>
        @else
            <li class="layim-msgbox-system">
                <p><em>系统：</em>{{$v['user']['username']}} @if($v['agree'] == 2)<span style="color: red"><em>拒绝了</em></span> @else<span style="color: #2aabd2">同意了@endif</span>{{$v['content']}}<span>{{$v['time']}}</span></p>
            </li>
        @endif
    @endforeach
@endif
    <div class="layui-flow-more"><li class="layim-msgbox-tips">暂无更多新消息</li></div>
</ul>
<script src="{{URL::asset('js/jquery-3.2.1.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('layui/layui.js')}}"></script>
<script type="text/javascript">
    var refuse_friend_url =  "{{URL::asset('gechat/msgbox/refuseFriend')}}";
    var gechat_url_init ="{{URL::asset('gechat/chatuser/init')}}";
    var agree_group_url = "{{URL::asset('gechat/chatuser/agreeGroup')}}";
    var refuse_group_url =  "{{URL::asset('gechat/chatuser/refuseGroup')}}";
    var msgbox_read_url = "{{URL::asset('gechat/msgbox/read')}}";
    var agree_friend_events = "{{URL::asset('gechat/chatuser/agreefriend/events')}}";
    //打开页面即把消息标记为已读
    layui.use(['jquery'], function () {
        var $ = layui.jquery;
        $.post(msgbox_read_url, {read: 0 , _token : '{{csrf_token()}}'}, function(){});
    });

    function agree(toid){
        layui.use(['layer'], function () {
            var layer = layui.layer
                , $ = layui.jquery;
            var friendIndex = parent.layer.open({
                type: 2,
                scrollbar: false
                ,title: '同意请求',
                content: "{{URL::asset('gechat/chatuser/agreefriend')}}"+"/"+toid,
                area: ['500px', '300px']
                , btn: ['同意', '返回']
                , yes: function (index, layero) {
                    var myGroupID = parent.layer.getChildFrame('#groupID', friendIndex);
                    var yourGroupID = parent.layer.getChildFrame('#friendGroupID', friendIndex);
                    var yourID = parent.layer.getChildFrame('#friendID', friendIndex);
                    console.log(friendIndex);
                    console.log(myGroupID);
                    console.log(yourGroupID);
                    console.log(yourID);
                    $.post(agree_friend_events,{
                        friendID : yourID.val(),
                        friendGroupID: yourGroupID.val(),
                        myGroupID : myGroupID.val(),
                        _token : '{{csrf_token()}}'
                    }, function (res) {
                        if(res.code == 0){
                            $.post(gechat_url_init,{
                                type: 'addFriend',
                                toid : toid,
                                id : '{{session('GEEK')}}' ,
                                GroupID: yourGroupID.val(),
                                YourGroupID: myGroupID.val(),
                                _token : '{{csrf_token()}}'
                            }, function (res) {
                                if(res.code != 0){
                                    parent.layer.msg(res.msg,{icon: 5});
                                }
                            },'json');
                            $.post(gechat_url_init,{
                                type: 'addFriend',
                                toid : '{{session('GEEK')}}',
                                id : toid,
                                GroupID: myGroupID.val(),
                                _token : '{{csrf_token()}}'
                            }, function (res){
                                if(res.code == 0){
                                    layer.close(friendIndex);
                                }
                                else{
                                    layer.msg(res.msg,{icon: 5});
                                }
                            },'json');
                        }
                        else{
                            layer.msg(res.msg,{icon: 6});
                        }
                    },'json');
                }
                , btn2: function (index, layero) {
                    parent.layer.close(friendIndex);
                }
                //return false 开启该代码可禁止点击该按钮关闭
                //,btn3: function(index, layero){
                //按钮【按钮三】的回调

                //return false 开启该代码可禁止点击该按钮关闭
                //}
                ,end:function () {
                    $('.btn-refresh').click();
                }
            });
        });
    }
    function refuse(id) {
        layui.use(['layim'], function () {
            var layim = layui.layim
                , layer = layui.layer
                , $ = layui.jquery;
                layer.open({
                content: '您确定拒绝吗？'
                , btn: ['确定', '再想想']
                , yes: function (index, layero) {
                    layer.msg('不开心', {icon: 5});
                    $.post(refuse_friend_url, {
                        '_token': '{{csrf_token()}}',
                        'from_id': id
                    }, function (res){
                        var data = JSON.parse(''+res+'');
                        if (data.code > 0) {
                            layer.msg(data.msg);
                        }
                        $('.btn-refresh').click()
                    });
                }
                , btn2: function (index, layero) {
                    layer.close(index);
                }
                    //return false 开启该代码可禁止点击该按钮关闭
                //,btn3: function(index, layero){
                //按钮【按钮三】的回调

                //return false 开启该代码可禁止点击该按钮关闭
                //}
                , cancel: function () {
                    //右上角关闭回调

                    //return false 开启该代码可禁止点击该按钮关闭
                }
            });

        });
    }
    function agreeG(fromID,groupID) {
        layui.use(['layim','layer'], function () {
            var $ = layui.jquery,
            layer = layui.layer;
            $.post(agree_group_url, {_token: "{{csrf_token()}}", 'from_id': fromID,'group_id': groupID}, function (res) {
                //console.log('第一次回调'+res);
                var data = JSON.parse(''+res+'');
                //console.log(data.code);
                if (data.code == 0){
                    $.post(gechat_url_init, {
                        type: 'addGroup',
                        join_id : fromID,
                        id: groupID,
                        groupname: data.data.groupName,
                        avatar: data.data.avatar,
                        _token: '{{csrf_token()}}'
                    }, function (res){
                        //console.log(res);不知道为什么第二次回调传回来的是对象，历史遗留吧！
                        if (res.code == 0){
                            //console.log('第二次回调');
                            $('.btn-refresh').click()
                        }
                    }, 'json');
                }
                else{
                    layer.msg(data.msg, {icon: 5});
                }
            });
        });
    }
    function refuseG(fromID,groupID) {
        layui.use(['layim','layer'] , function () {
            var layim = layui.layim
                , layer = layui.layer
                , $ = layui.jquery;
            layer.open({
                content: '您确定拒绝吗？'
                , btn: ['确定', '再想想']
                , yes: function (index, layero) {
                    layer.msg('不开心。。', {icon: 5});
                    $.post(refuse_group_url, {
                        '_token': '{{csrf_token()}}',
                        'from_id': fromID,
                        'from_group' : groupID
                    }, function (res) {
                        var data = JSON.parse(''+res+'');
                        if (data.code > 0) {
                            layer.msg(data.msg);
                        }
                        $('.btn-refresh').click()
                    });
                }
                , btn2: function (index, layero) {
                    layer.close(index);
                }
                //return false 开启该代码可禁止点击该按钮关闭
                //,btn3: function(index, layero){
                //按钮【按钮三】的回调

                //return false 开启该代码可禁止点击该按钮关闭
                //}
                , cancel: function () {
                    //右上角关闭回调

                    //return false 开启该代码可禁止点击该按钮关闭
                }
            });

        });
    }
</script>
</body>
</html>