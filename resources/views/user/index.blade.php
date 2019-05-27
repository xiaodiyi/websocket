<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn">
<head>
    <title>极云赋-v2.0</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="zh-CN"/>
    <meta name="Author" content="黄宇轩"/>
    <meta name="Copyright" content="极客聊天"/>
    <meta name="keywords" content="极云赋,聊天,极客,QQ,平台,便捷,客服"/>
    <meta name="description" content="极客聊天-GeChat是基于layim和Laravel的一款极为便捷的Web端即时通讯系统;虽然'前无古人',但我还是努力去实现GeChat的'聊天梦',让我们携手彼此,开启Web聊天新时代 ^_^" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">

    <link rel="stylesheet" type="text/css" href="{{URL::asset('layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('org/jquery-menu/css/jquery-menu.css')}}" media="all">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('org/font-awesome-4.7.0/css/font-awesome.min.css')}}" media="all">
    {{----}}
    <style>.layui-layim-user{cursor: pointer}</style>
    <!-- Favicon and touch icons -->
    <link rel="shortcut icon" href="{{URL::asset('images/icon/favicon.ico')}}">
    <link rel="apple-touch-icon" href="{{URL::asset('images/icon/apple-touch-icon.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{URL::asset('images/icon/apple-touch-icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{URL::asset('images/icon/apple-touch-icon-114x114.png')}}">
</head>
<style>
    .layim-chat-text img{
        max-height: 150px;
    }
    .catMenu{
        z-index: 1000000000;
    }
    body .chat-history-layer-control {
        z-index: 19891020;
        width: 450px!important;
        height: 911px;
        top: -2px!important;
        left: 455px;
        position: fixed;
        overflow: visible;
    }
    body .gechat-msg-shield{
        display: block;
        position: absolute;
        bottom: 7px;
        padding: 0 5px;
        height: 16px;
        line-height: 16px;
        border-radius: 16px;
        text-align: center;
        font-size: 10px;
        background-color: #F74C31;
        color: #fff;
    }
    body .gechat-msg-shield-friend{
        display: block;
        position: absolute;
        right: 0px;
        bottom: 7px;
        padding: 0 5px;
        height: 16px;
        line-height: 16px;
        border-radius: 16px;
        text-align: center;
        font-size: 10px;
        background-color: #F74C31;
        color: #fff;
    }
    .catMenu a{
        margin-left: 2px;
    }
    .catMenu a i{
        margin-right: 6px;
    }
</style>
<body>
<pre style="padding-top: 50px;padding-left: 50px">
    已实现功能:<br>
        一.好友
            1.基本的注册/登陆实现；
            2.修改个人资料/群资料的实现；
            3.修改个性签名&&在线隐身状态切换；
            4.好友/群内的文字、表情、图片、文件的发送和接收；
            5.消息盒子->同意/拒绝->申请好友||加群消息提醒;
            6.消息盒子->同意/拒绝->加群请求||好友请求；
            7.离线消息；
            8.异地登陆被迫挤下线提醒;

        二.群
            1.查找好友/群组实现；
            2.创建/申请/解散群组;
            3.聊天记录并实现分页查询；

        三.进阶：右键菜单
            好友：
                1.查看好友资料；
                2.查看好友空间;
                3.屏蔽/接受消息；
                4.删除好友；
                5.移动至~
                6.举报好友；
                7.好友分组->增、删、改；
            群组：
                # 1~6略；
                7.退出群组；
            主菜单：
                1.切换账号；
                2.退出账号；
                3.用户中心；

        四.高级：用户中心
            1.管理我创建的群组;
            2.同步修改资料功能到用户中心；
            3.修改密码操作；
            4.地址本；

    ####---<span style="color: red;">友情提示：没有刷新此网页解决不了的问题！！！</span>---###

    新增好友/群或移除好友/群后，有时候会出现右键菜单失效的问题，刷新此网页即可解决；
</pre>
<div class="layim-gechat-test"></div>
<!-- audio box start-->
<input type="hidden" id="audio_src" name="audio-blob"/>
<div class="wrapper wrapper-content animated" id="audio_box" style="display: none;margin-top: 10px">
    <div class="row">
        <span id="tips" style="margin-left:26%; color:red;"></span>
        <div class="col-sm-12" style="text-align: center;margin-top:10px">
            <img src="{{URL::asset('layui/layui.js')}}" width="100px" height="100px" id="a_pic"/>
        </div>
    </div>
    <div class="col-sm-4 col-sm-offset-4" style="margin:10px 0px 0px 20px">
        <button class="layui-btn layui-btn-small" id="say">开始说话</button>
        <button class="layui-btn layui-btn-small layui-btn-danger layui-btn-disabled" id="over—say">结束发送</button>
    </div>
</div>
<!-- audio box end-->
{{--contextOrg start--}}

<div id="friendMenu" style="display: none">
    <ul>
        <li id="MenuItem1"><a href="#look"><i class="fa fa-vcard"></i>查看资料</a></li>
        <li id="MenuItem19"><a href="#gzone"><i class="fa fa-paper-plane"></i>查看空间</a></li>
        <li id="MenuItem2"><a href="#history"><i class="fa fa-history"></i>历史记录</a></li>
        <li id="MenuItem14"><a href="#shield"><i class="fa fa-ban"></i>屏蔽/接受消息</a></li>
        <li id="MenuItem3"><a href="#delete"><i class="fa fa-trash"></i>删除好友</a></li>
        <li id="MenuItem4"><a href="#moveTo"><i class="fa fa-mail-forward"></i>移动至</a>
            <ul style="left: -140px!important;">
                @foreach($group as $key => $v)
                <li id="{{'MenuItem4.'.$v->group_id}}"><a href="#moveItem"><i class="fa fa-paper-plane"></i>{{$v->group_name}}</a></li>
                @endforeach
            </ul>
        </li>
        <li id="MenuItem16"><a href="#report"><i class="fa fa-fire"></i>举报好友</a></li>
    </ul>
</div>
<div id="groupMenu" style="display: none">
    <ul>
        <li id="MenuItem5"><a href="#refresh"><i class="fa fa-refresh"></i>刷新好友</a></li>
        <li id="MenuItem6"><a href="#create"><i class="fa fa-pencil"></i>新建分组</a></li>
        <li id="MenuItem7"><a href="#delete"><i class="fa fa-trash"></i>删除分组</a></li>
        <li id="MenuItem8"><a href="#rename"><i class="fa fa-edit"></i>修改名称</a></li>
    </ul>
</div>
<div id="chatGroupMenu" style="display: none">
    <ul>
        <li id="MenuItem9"><a href="#look"><i class="fa fa-vcard "></i>查看群资料</a></li>
        <li id="MenuItem10"><a href="#shield"><i class="fa fa-ban"></i>屏蔽/接受消息</a></li>
        <li id="MenuItem11"><a href="#history"><i class="fa fa-history"></i>历史记录</a></li>
        <li id="MenuItem17"><a href="#create"><i class="fa fa-trash"></i>创建群组</a></li>
        <li id="MenuItem18"><a href="#manage"><i class="fa fa-sign-out"></i>管理群组</a></li>
        <li id="MenuItem15"><a href="#report"><i class="fa fa-fire"></i>举报/反馈</a></li>
        <li id="MenuItem13"><a href="#leave"><i class="fa fa-sign-out"></i>退出该群</a></li>
    </ul>
</div>
<div id="registerMenu" style="display: none">
    <ul>
        <li id="MenuItem11"><a href="#switch"><i class="fa fa-mail-forward"></i>切换账号</a></li>
        <li id="MenuItem16"><a href="#logout"><i class="fa fa-sign-out"></i>我要退出</a></li>
        <li id="MenuItem17"><a href="#ucenter"><i class="fa fa-user"></i>用户中心</a></li>
    </ul>
</div>
<script type="text/javascript">
    var my_events;
    var user_list_url = "{{URL::asset('gechat/index/getList')}}";
    var member_list_url = "{{URL::asset('gechat/index/getMembers')}}";
    var upload_img_url = "{{URL::asset('gechat/upload/uploadImg')}}";
    var upload_file_url = "{{URL::asset('gechat/upload/uploadFile')}}";
    var msg_box_url = "{{URL::asset('gechat/msgbox/index')}}";
    var find_url = "{{URL::asset('gechat/chatuser/findgroup')}}";
    var chatlog_url = "{{URL::asset('gechat/chatuser/chatlog')}}";
    var change_sign_url = "{{URL::asset('gechat/chatuser/changeSign')}}";
    var uid = "{{$uinfo->id}}";
    var uname = "{{$uinfo->user_name}}";
    var avatar = "{{$uinfo->avatar}}";
    var sign = "{{$uinfo->sign}}";
    var gechat_url_init ="{{URL::asset('gechat/chatuser/init')}}";
    var chat_user_url = "{{URL::asset('gechat/chatuser/index')}}";
    var save_audio_url = "{{URL::asset('gechat/tools/saveAudio')}}";
    var join_group_url = "{{URL::asset('findgroup/joinDetail')}}";
    var msg_noread_url = "{{URL::asset('gechat/msgbox/getNoRead')}}";
    var friendID ;//用来存放要右键菜单操作的元素ID(朋友);
    var groupName ;//用来存放要右键菜单操作的元素ID(分组);
    var chatGroupID ;//用来存放要右键菜单操作的元素ID(群组);
    var delete_friend_url = "{{URL::asset('gechat/chatuser/deleteFriend')}}";
    var move_group_url  = "{{URL::asset('gechat/chatuser/moveGroup')}}";
    var shield_group_url = "{{URL::asset('gechat/chatuser/shield')}}";
    var destroy_chatGroup_url = "{{URL::asset('gechat/chatuser/destroyChatGroup')}}";
    var destroy_friendGroup_url = "{{URL::asset('gechat/chatuser/destroyFriendGroup')}}";
    var look_friend_url = "{{URL::asset('gechat/chatuser/lookFriend')}}";
    var look_group_url = "{{URL::asset('gechat/chatuser/lookGroup')}}";
    var leave_chatGroup_url = "{{URL::asset('gechat/chatuser/leaveChatGroup')}}";
    var report_friend_url = "{{URL::asset('gechat/chatuser/reportFriend')}}";
    var report_group_url = "{{URL::asset('gechat/chatuser/reportGroup')}}";
    var gechat_switch_url = "{{URL::asset('gechat/chatuser/switch')}}";
    var gechat_logout_url = "{{URL::asset('gechat/chatuser/logout')}}";
    var create_group_url = "{{URL::asset('gechat/chatuser/createGroup')}}";
    var my_chatGroup_url = "{{URL::asset('gechat/ucenter')}}";
    var look_gzone_url = "{{URL::asset('gechat/chatuser/lookGzone')}}";
    {{--好友分组接口--}}
    var create_friend_group_url =  "{{URL::asset('gechat/chatuser/createFriendGroup')}}";
    var delete_friend_group_url =  "{{URL::asset('gechat/chatuser/deleteFriendGroup')}}";
    var rename_friend_group_url =  "{{URL::asset('gechat/chatuser/renameFriendGroup')}}";

</script>
<script src="{{URL::asset('js/jquery-2.1.4.min.js')}}"></script>
{{--<script type="text/javascript" src="{{URL::asset('js/RecordRTC.js')}}"></script>--}}
<script type="text/javascript" src="{{URL::asset('js/audio.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('layui/layui.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('org/jquery-menu/js/jquery-menu.js')}}"></script>{{----}}
<script type="text/javascript" src="{{URL::asset('org/jquery-menu/js/jquery.hoverintent.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('js/index.js')}}"></script>
<script type="text/javascript">
    {{-- 一直报错layui.js:2 Layui hint: socket is not a valid module,他妈的，直接引入插件了；
        layui.config({
            base: '/layui/res/js/modules/' //扩展 JS 所在目录
        }).extend({
            contextMenu:'contextMenu'
        });
        --}}

    layui.config({
        base: '/layui/res/js/modules/' //扩展 JS 所在目录
    }).extend({
    });
    layui.use(['layim', 'jquery'], function (layim) {
        var layer = layui.layer;
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        layim.config({
            init: {
                url: user_list_url
                ,type: 'post'
                ,data: {}
            }
            //获取群员接口（返回的数据格式见下文）
            ,members: {
                url: member_list_url //接口地址（返回的数据格式见下文）
                ,type: 'get' //默认get，一般可不填
                ,data: {} //额外参数
            }

            //上传图片接口（返回的数据格式见下文），若不开启图片上传，剔除该项即可
            ,uploadImage: {
                url: upload_img_url //接口地址
                ,type: 'post' //默认post
            }

            //上传文件接口（返回的数据格式见下文），若不开启文件上传，剔除该项即可
            ,uploadFile: {
                url: upload_file_url //接口地址
                ,type: 'post' //默认post
            }
            //扩展工具栏，下文会做进一步介绍（如果无需扩展，剔除该项即可）
            ,tool: [
                {
                    alias: 'voice' //工具别名
                    ,title: '语音'  //工具名称
                    ,icon: '&#xe62c;' //工具图标
                },
                {
                    alias: 'code' //工具别名
                    ,title: '代码' //工具名称
                    ,icon: '&#xe64e;' //工具图标，参考图标文档
                }
            ]
            ,initSkin: '3.jpg'
            ,msgbox: msg_box_url //消息盒子页面地址，若不开启，剔除该项即可
            ,find: find_url //发现页面地址，若不开启，剔除该项即可
            ,chatLog: chatlog_url //聊天记录页面地址，若不开启，剔除该项即可
        });
        socket = new WebSocket('ws://gechat.nat123.net:13014');
        //连接成功时触发
        socket.onopen = function(){
            // 登录
            console.log("websocket握手成功!");
        };
        //监听收到的消息
        socket.onmessage = function(e) {
            var data = JSON.parse(e.data),
                type = data.type || '',
                message = data.data || '';
            switch (type) {
                case 'init':
                    var client_id = data.client_id || '';
                    $.post(gechat_url_init,{
                        type: 'init',
                        client_id : client_id,
                        id : uid ,
                        username : uname,
                        avatar : avatar,
                        sign : sign
                    }, function (res) {
                    },'json');
                    console.log('已发送初始化json');
                    break;
                case 'online':
                    console.log(data.id+"已上线");
                    layim.setFriendStatus(data.id, 'online');
                    break;
                case 'offline':
                    console.log(data.id+"已下线");
                    layim.setFriendStatus(data.id, 'offline');
                    break;
                case 'hide':
                    console.log(data.id+"已隐身");
                    layim.setFriendStatus(data.id, 'hide');
                    break;
                case 'account_conflict':
                    parent.layer.alert('您的账号在别处成功登陆，您被迫下线', {
                        skin: 'layui-layer-lan'
                        ,closeBtn: 0
                        ,title : '警告'
                        ,anim: 4 //动画类型
                    }, function(){
						$.post(gechat_logout_url, {
                            }, function (res) {
						        JSON.parse(''+res+'');
						        if(res.code == 0){
                                    $.post(gechat_url_init,{
                                        type: 'online',
                                        status: 'offline',
                                        id : uid ,
                                        username : uname,
                                        avatar : avatar,
                                        sign : sign
                                    }, function (res){
                                    },'json');
                                    //window.open(my_chatGroup_url);
                                    console.log('已发送离线状态');
                                    window.location.reload();
                                }
                                else{
                                    console.log('第二次尝试清除session');
                                    $.post(gechat_logout_url, {
                                    }, function (res){
                                        window.location.reload();
                                    });
                                }

                            });
						});
                    break;
                case 'notice' :
                    console.log(data.content);
                    break;
                case 'addFriend':
                    console.log("增加一个新朋友！");
                    layim.addList(data.data);
                    break;
                case 'logMessage':
                    console.log(data.data+'\n'+'这是一个离线消息！');
                    setTimeout(function(){layim.getMessage(data.data)}, 1000);
                    break;
                case 'chatMessage':
                    if(message.from_id == uid){
                        break;
                    }
                    @if(!empty($shield))
                        @foreach($shield as $key =>$v)
                            if(message.id == '{{$v->put_id}}'){
                                break;
                            }

                        @endforeach
                    @endif
                    if(message.type == 'friend'){
                            $('body .layim-friend'+message.id+' .layim-msg-status').css('display','block');
                        }
                        else{
                            $('body .layim-group'+message.id+' .layim-msg-status').css('display','block');
                        }
                    console.log('这是一个在线消息！');
                    layim.getMessage(data.data); //res.data即你发送消息传递的数据（阅读：监听发送的消息）
                    break;
                // 添加 分组信息
                case 'addGroup':
                    console.log(data.data);
                    layim.addList(data.data);
                    break;
                //从主面板移除好友
                case 'delFriend':
                    layim.removeList({
                        type: 'friend',
                        id: message.id //好友或者群组ID
                    });
                    console.log('已删除好友'+message.id);
                    break;
                //从主面板移除好友
                case 'desChatGroup':
                    layim.removeList({
                        type: 'group',
                        id: message.id //好友或者群组ID
                    });
                    console.log('该群组被解散：'+message.id);
                    break;
                case 'leaveChatGroup':
                    layim.removeList({
                        type: 'group',
                        id: message.id //好友或者群组ID
                    });
                    console.log('您已经退出此群：'+message.id);
                    break;
            }
        };
        //layim建立就绪
        layim.on('ready', function(res){
            @if(!empty($shield))
                @foreach($shield as $key =>$v)
                    @if($v->type == 'group')
                    $('body .layim-group{{$v->put_id}}').append("<span class=\"gechat-msg-shield\"><i class=\"fa fa-ban\"></i></span>");
                    //$('body .layim-gechat-test')[0].innerHTML="<span class=\"gechat-msg-shield\">已屏蔽</span>";
                    @else
                    $('body .layim-friend{{$v->put_id}}').append("<span class=\"gechat-msg-shield-friend\"><i class=\"fa fa-ban\"></i></span>");
                    @endif
                @endforeach
            @endif
            //查询有无新消息
            $.post(msg_noread_url, function (e) {
                var data = JSON.parse(e);
                if (data.data > 0) {
                    layim.msgbox(data.data);
                }
            });
            setInterval(function () {
                $.post(msg_noread_url, function (e) {
                    var data = JSON.parse(e);
                    if (data.data > 0) {
                        layim.msgbox(data.data);
                    }
                });
            }, parseInt(120) * 1000);
            //点击头像操作
            $(".layui-layim-user").click(function(){
                layer.open({
                    type: 2,
                    title: '修改个人资料',
                    maxmin: true,//开启最大化最小化按钮
                    area: ['500px', '700px'],
                    content: chat_user_url
                });
            });
            $('li[layim-event="chat"]').click(function () {
                var chat_type = $(this).attr('data-type');
                var target  = null;
                if(chat_type == 'friend'){
                    target = $(this).attr('class').split("-",2)[1].substring(6);
                    $('body .layim-friend'+target+' .layim-msg-status').css('display','none');
                }//.split("-",2)[1].substring(0,6);
                else{
                    target = $(this).attr('class').split("-",2)[1].substring(5);
                    $('body .layim-group'+target+' .layim-msg-status').css('display','none');
                }
                console.log(target);

            });
            $('li[data-type="friend"]').catMenu({
                menu: 'friendMenu',
                on_load: function(e) {
                    $(this).mousedown(function(e){
                        if(3 == e.which){
                            if(e.target.offsetParent.className == 'layui-layim-main'){
                                friendID = e.target.className.split(" ",1)[0].substring(12);
                                console.log(friendID);
                            }
                            else{
                                friendID = e.target.parentElement.className.split(" ",1)[0].substring(12);
                                console.log(friendID);
                            }
                        }
                    });
                },
                on_select: function(e) {
                    switch(e.action){
                        case 'delete' :
                            layer.confirm('确定删除该好友？', {
                                btn: ['确定', '取消'],
                                title: '友情提示',
                                closeBtn: 0,
                                icon: 3
                            }, function(index){
                                $.post(delete_friend_url,{
                                    friendID: friendID
                                },function (res){
                                    if(res.code == 0){
                                        layim.removeList({
                                            type: 'friend'
                                            , id: friendID
                                        });
                                        $.post(gechat_url_init, {
                                            type: 'delFriend',
                                            to_id : friendID
                                        }, function (res){
                                            if(res.code == 0){
                                                layer.msg(res.msg, {icon: 1});
                                                layer.close(index);
                                            }
                                            else{
                                                layer.msg(res.msg, {icon: 7});
                                            }
                                        }, 'json');
                                    }
                                    else{
                                        layer.msg(res.msg, {icon: 7});
                                    }

                                }, 'json');

                            }); break;
                        case 'moveItem':
                            var to_group_id = e.id.substring(10);
                            $.post(move_group_url, {
                                move_friend_id : friendID,
                                to_group_id    : to_group_id
                            }, function (res) {
                                if(res.code == 0){
                                    layim.removeList({
                                        type: 'friend'
                                        ,id: friendID
                                    });
                                    layim.addList({
                                        type: 'friend'
                                        ,avatar: res.data.avatar
                                        ,username: res.data.user_name
                                        ,groupid: to_group_id
                                        ,id:friendID
                                        ,sign: res.data.sign
                                    });
                                    layer.msg(res.msg, {icon: 1});

                                }
                                else{
                                    layer.msg(res.msg, {icon: 7});
                                }
                            }, 'json');
                            break;

                        case 'history':
                            layer.open({
                                type: 2
                                ,title: '聊天记录'
                                ,maxmin: true
                                ,content: "{{URL::asset('gechat/chatuser/chatlogs/')}}"+"/"+friendID+""
                                ,skin: 'chat-history-layer-control'
                            });
                            $("body .chat-history-layer-control").css("left",$(document).width()-450);
                            break;
                        case 'shield' :
                            $.post(shield_group_url, {
                                put_id    : friendID
                                ,type : 'friend'
                            }, function (res){
                                if(res.code == 0){
                                    layer.msg(res.msg, {icon: 1});
                                }
                                else{
                                    layer.msg(res.msg, {icon: 7});
                                }
                            }, 'json');
                            break;
                        case 'look' :
                            layer.open({
                                type: 2
                                ,title: '好友资料'
                                ,maxmin: true
                                ,content: look_friend_url+"/"+friendID+""
                                ,area: ['500px', '700px']
                                ,skin: ''
                            });
                            break;
                        case 'gzone' :
                            var index= layer.open({
                                type: 2
                                ,title: '好友空间'
                                ,maxmin: true
                                ,content: look_gzone_url+"/"+friendID+""
                                ,skin: 'layui-layer-molv' //加上边框
                                ,area: ['500px', '600px']
                                ,shade: 0.3
                                ,shadeClose: true
                            });
                            parent.layer.full(index);
                            break;
                        case 'report' :
                            layer.open({
                                type: 2,
                                title: '举报好友',
                                shadeClose: true,
                                maxmin: true,
                                skin: 'layui-layer-molv', //加上边框
                                shade: 0.3,
                                content: report_friend_url+"/"+friendID+""
                                ,area: ['500px', '600px']
                            });
                            break;
                        default:break;
                    }
                }
            });
            $('li[data-type="group"]').catMenu({
                menu: 'chatGroupMenu',
                on_load: function(e){
                    $(this).mousedown(function(e){
                        if(3 == e.which){
                            if(e.target.offsetParent.className == 'layui-layim-main'){
                                chatGroupID = e.target.className.split(" ",1)[0].substring(11);
                                console.log(chatGroupID);
                            }
                            else{
                                chatGroupID = e.target.parentElement.className.split(" ",1)[0].substring(11);
                                console.log(chatGroupID);
                            }
                        }
                    });
                },
                on_select: function(e) {
                    switch(e.action){
                        case 'look' :
                            layer.open({
                                type: 2
                                ,title: '群组资料'
                                ,scrollbar: false
                                ,maxmin: true
                                ,content: look_group_url+"/"+chatGroupID+""
                                ,area: ['500px', '700px']
                                ,skin: ''
                            });
                            break;
                        case 'shield' :
                            $.post(shield_group_url, {
                                put_id    : chatGroupID
                                ,type : 'group'
                            }, function (res) {
                                if(res.code == 0){
                                    layer.msg(res.msg, {icon: 1});
                                }
                                else{
                                    layer.msg(res.msg, {icon: 7});
                                }
                            }, 'json');
                            break;
                        case 'destroy' :
                            layer.confirm('确定解散该群组？', {
                                btn: ['确定', '取消'],
                                title: '友情提示',
                                closeBtn: 0,
                                icon: 3
                            }, function(index){
                                $.post(destroy_chatGroup_url, {
                                    group_id    : chatGroupID
                                    ,type : 'group'
                                }, function (res) {
                                    if(res.code == 0){
                                        $.post(gechat_url_init, {
                                            type: 'desChatGroup',
                                            to_id : chatGroupID
                                        }, function (res){
                                            if(res.code == 0){
                                                layer.msg(res.msg, {icon: 1});
                                            }
                                            else{
                                                layer.msg(res.msg, {icon: 7});
                                            }
                                        }, 'json');
                                    }
                                    else{
                                        layer.msg(res.msg, {icon: 7});
                                    }
                                }, 'json');
                            });

                            break;
                        case 'leave' :
                            layer.confirm('确定退出群组？', {
                                btn: ['确定', '取消'],
                                title: '友情提示',
                                closeBtn: 0,
                                icon: 3
                            }, function(index) {
                                $.post(leave_chatGroup_url, {
                                    groupID: chatGroupID
                                    , type: 'group'
                                }, function (res) {
                                    if (res.code == 0) {
                                        $.post(gechat_url_init, {
                                            type: 'leaveChatGroup',
                                            to_id: chatGroupID
                                        }, function (res) {
                                            if (res.code == 0) {
                                                layer.msg(res.msg, {icon: 1});
                                            }
                                            else {
                                                layer.msg(res.msg, {icon: 7});
                                            }
                                        }, 'json');
                                    }
                                    else {
                                        layer.msg(res.msg, {icon: 7});
                                    }
                                }, 'json');
                            });
                            break;
                        case 'report' :
                            layer.open({
                                type: 2,
                                title: '举报群组',
                                shadeClose: true,
                                maxmin: true,
                                skin: 'layui-layer-molv', //加上边框
                                shade: 0.3,
                                content: report_group_url+"/"+chatGroupID+""
                                ,area: ['500px', '600px']
                            });
                            break;
                        case 'history':
                            layer.open({
                                type: 2
                                ,title: '聊天记录'
                                ,maxmin: true
                                ,content: "{{URL::asset('gechat/chatuser/groupChatlogs/')}}"+"/"+chatGroupID+""
                                ,skin: 'chat-history-layer-control'
                            });

                            $("body .chat-history-layer-control").css("left",$(document).width()-450);
                            break;
                        case 'create':
                            var index = layer.open({
                                    type: 2,
                                    title: '创建群组',
                                    content: create_group_url,
                                    area: ['500px', '500px']
                                });
                            break;
                        case 'manage':
                            var index = parent.layer.open({
                                type: 2
                                ,title: '我的群组'
                                ,content: my_chatGroup_url
                                ,maxmin: true
                                ,area: ['500px', '500px']
                            });
                            parent.layer.full(index);
                            break;
                        default:  break;

                    }
                }
            });
            $('h5').catMenu({
                menu: 'groupMenu',
                on_load: function(e){
                    $(this).mousedown(function(e){
                        if(3 == e.which){
                            groupName = $(this).text();
                            console.log(groupName.split("(",1)[0].substring(1));
                            groupName = groupName.split("(",1)[0].substring(1);
                        }
                    });
                },
                on_select: function(e) {
                    switch(e.action) {
                        case 'create' :
                            layer.prompt(function(value, index){
                                console.log(value.length);
                                if(value.length <= 10){
                                    $.post(create_friend_group_url,{
                                        new_group_name : value
                                    },function (res){
                                        var data = JSON.parse(''+res+'');
                                        if(data.code == 0){
                                            parent.layer.msg(data.msg,{icon:1});
                                            setTimeout(function () {
                                                location.reload();
                                            },2000);
                                        }
                                        else{
                                            parent.layer.msg(data.msg,{icon:7});
                                        }
                                    });
                                    layer.close(index);
                                }
                                else{
                                    parent.layer.msg('分组名称不能超过10个字符',{icon:7});
                                }

                            });
                            break;
                        case 'rename' :
                            layer.prompt(function(value, index){
                                console.log(value.length);
                                if(value.length <= 10) {
                                    $.post(rename_friend_group_url, {
                                        old_group_name: groupName,
                                        new_group_name: value
                                    }, function (res) {
                                        var data = JSON.parse('' + res + '');
                                        if (data.code == 0) {
                                            parent.layer.msg(data.msg, {icon: 1});
                                            setTimeout(function () {
                                                location.reload();
                                            }, 2000);
                                        }
                                        else {
                                            parent.layer.msg(data.msg, {icon: 7});
                                        }
                                    });
                                    layer.close(index);
                                }
                                else{
                                    parent.layer.msg('分组名称不能超过10个字符',{icon:7});
                                }
                            });
                            break;
                        case 'delete' :
                            layer.confirm('确定删除该分组？', {
                                btn: ['确定', '取消'],
                                title: '友情提示',
                                closeBtn: 0,
                                icon: 3
                            }, function(index){
                                $.post(delete_friend_group_url,{
                                    group_name : groupName
                                },function (res){
                                    var data = JSON.parse(''+res+'');
                                    if(data.code == 0){
                                        parent.layer.msg(data.msg,{icon:1});
                                        setTimeout(function () {
                                            location.reload();
                                        },2000);
                                    }
                                    else{
                                        parent.layer.msg(data.msg,{icon:7});
                                    }
                                });
                                layer.close(index);
                            });
                            break;
                    }
                }
            });
            $('.layui-layim-info').catMenu({
                menu: 'registerMenu',
                mouse_button: 'right',
                on_select: function(e) {
                    switch(e.action){
                        case 'switch':
                            $.post(gechat_switch_url, {
                            }, function (res){
                                if(res.code == 0){
                                    $.post(gechat_url_init,{
                                        type: 'online',
                                        status: 'offline',
                                        id : uid ,
                                        username : uname,
                                        avatar : avatar,
                                        sign : sign
                                    }, function (res) {
                                    },'json');
                                    console.log('已发送离线状态');
                                    layer.msg('稍等哒~正在清楚您的登陆信息...', {
                                        icon: 16
                                        ,shade: 0.01
                                    });
                                    setTimeout(function (){
                                        window.location.reload();
                                    },2500);

                                }
                                else{
                                    layer.msg(res.msg, {icon: 7});
                                }
                        }, 'json');
                            break;
                        case 'logout':
                            $.post(gechat_logout_url, {
                            }, function (res){
                                if(res.code == 0){
                                    $.post(gechat_url_init,{
                                        type: 'online',
                                        status: 'offline',
                                        id : uid ,
                                        username : uname,
                                        avatar : avatar,
                                        sign : sign
                                    }, function (res) {
                                    },'json');
                                    console.log('已发送离线状态');
                                    layer.msg('稍等哒~正在清楚您的登陆信息...', {
                                        icon: 16
                                        ,shade: 0.01
                                    });
                                    setTimeout(function (){
                                        window.opener=null;
                                        window.open('','_self');
                                        window.close();   //这个就是JS不带任何提示关闭窗口的代码
                                    },2500);
                                }
                                else{
                                    layer.msg(res.msg, {icon: 7});
                                }
                            }, 'json');
                            break;
                        case 'ucenter':
                            var index = parent.layer.open({
                                type: 2
                                ,title: '用户中心'
                                ,content: my_chatGroup_url
                                ,maxmin: true
                                ,area: ['500px', '500px']
                            });
                            parent.layer.full(index);
                            break;
                    }
                }
            });
        });
        layim.on('sign', function(value){
            $.post(change_sign_url, {'sign' : value,'_token':'{{csrf_token()}}'},function(res){
                if(1 == res.code){
                    layer.msg(res.msg, {time:1500});
                }else{
                    layer.msg(res.msg, {time:1500});
                }
            }, 'json');
        });
        //在线状态切换
        layim.on('online', function(status){
            $.post(gechat_url_init,{
                type: 'online',
                status: status,
                id : uid ,
                username : uname,
                avatar : avatar,
                sign : sign,
                _token : '{{csrf_token()}}'

            }, function (res) {
            },'json');
            console.log('已发送在线状态'+status);
        });
        layim.on('sendMessage', function(res){
            //console.log(res);
            if(res.to.type === 'friend'){
                layim.setChatStatus('<span style="color:#333333;">对方正在输入...</span>');
            }
            // 发送消息
            var mine = JSON.stringify(res.mine);
            var to = JSON.stringify(res.to);
            var login_data = '{"data":{"mine":'+mine+', "to":'+to+'}}';
            console.log(login_data);
            $.post(gechat_url_init,{
                type: 'chatMessage',
                mine: mine,
                to : to
            }, function (res) {
            },'json');
        });
        layim.on('tool(code)', function(insert, send, obj){ //事件中的tool为固定字符，而code则为过滤器，对应的是工具别名（alias）
            layer.prompt({
                title: '插入代码'
                ,formType: 2
                ,shade: 0
            }, function(text, index){
                layer.close(index);
                insert('[pre class=layui-code]' + text + '[/pre]'); //将内容插入到编辑器，主要由insert完成
                //send(); //自动发送
            });
            console.log(this); //获取当前工具的DOM对象
            console.log(obj); //获得当前会话窗口的DOM对象、基础信息
        });
        //监听自定义工具栏点击录音
        {{--layim.on('tool(voice)', function(insert, send){
            layui.use(['layer'], function(){
                var layer = layui.layer;
                var box = layer.open({
                    type: 1,
                    title: '发送语音',
                    maxmin: true, //开启最大化最小化按钮
                    skin: 'layui-layer-molv',
                    anim: 3,
                    area: ['200px', '250px'],
                    content: $("#audio_box"),
                    cancel: function(index){
                        $("#tips").text('');
                        $("#a_pic").attr('src', '/static/common/images/audio.png');
                        layer.close(index);
                        return false;
                    }
                });


                //点击开始录音
                $("#say").bind('click', function(){
                    $("#tips").text('说话中......');
                    $("#a_pic").attr('src', '/static/common/images/audio.gif');
                    $("#over—say").removeClass('layui-btn-disabled');
                    $(this).addClass('layui-btn-disabled');
                });
                var isSend = false;
                //结束录音
                $("#over—say").bind('click', function(){
                    if(!isSend){
                        $("#tips").text('');
                        $("#a_pic").attr('src', '/static/common/images/audio.png');
                        $("#say").removeClass('layui-btn-disabled');
                        $(this).addClass('layui-btn-disabled');
                        layer.close(box);

                        var index = layer.load(1, {
                            shade: [0.1,'#fff'] //0.1透明度的白色背景
                        });
                        setTimeout(function(){
                            var audioSrc = $("#audio_src").val();
                            if('' == audioSrc){
                                layer.msg('您没有发送语音', {time:1000});
                            }else{
                                insert('audio[' + audioSrc + ']');
                                send(); //自动发送
                            }
                            layer.close(index);
                        }, 1500);
                        isSend = true;
                    }
                });
            });

        });--}}
    });
</script>
</body>
</html>