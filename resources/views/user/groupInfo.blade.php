<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>极云赋-群组管理</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('org/larry-admin/common/global.css')}}" media="all">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('org/larry-admin/common/bootstrap/css/bootstrap.css')}}" media="all">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('org/larry-admin/css/main.css')}}" media="all">
</head>
<style>
    .quick-operation{
        text-align: left;
        margin-top: 10px;
    }
    body .layui-layim-members{
        margin: 0!important;
    }
    .layui-layim-members ul{
        width: auto !important;
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
<section class="larry-wrapper">
    <!-- overview -->
    <div class="row state-overview">
        <div class="col-lg-3 col-sm-6 layui-anim layui-anim-up">
            <section class="panel">
                <div class="symbol userblue layui-anim layui-anim-rotate"> <i class="iconpx-users"></i>
                </div>
                <div class="value">
                    <a href="#">
                        <h1 id="count1">{{$groupMembers['count']}}</h1>
                    </a>
                    <p>群员总数</p>
                </div>
            </section>
        </div>
        <div class="col-lg-3 col-sm-6 layui-anim layui-anim-up">
            <section class="panel">
                <div class="symbol commred layui-anim layui-anim-rotate"> <i class="iconpx-user-add"></i>
                </div>
                <div class="value">
                    <a href="#">
                        <h1 id="count2">0</h1>
                    </a>
                    <p>今日加入</p>
                </div>
            </section>
        </div>
        <div class="col-lg-3 col-sm-6 layui-anim layui-anim-up">
            <section class="panel">
                <div class="symbol articlegreen layui-anim layui-anim-rotate">
                    <i class="iconpx-file-word-o"></i>
                </div>
                <div class="value">
                    <a href="#">
                        <h1 id="count3">1</h1>
                    </a>
                    <p>管理员总数</p>
                </div>
            </section>
        </div>
        <div class="col-lg-3 col-sm-6 layui-anim layui-anim-up">
            <section class="panel">
                <div class="symbol rsswet layui-anim layui-anim-rotate">
                    <i class="iconpx-check-circle"></i>
                </div>
                <div class="value">
                    <a href="#">
                        <h1 id="count4">0</h1>
                    </a>
                    <p>待审核</p>
                </div>
            </section>
        </div>
    </div>
    <!-- overview end -->
    <div class="row">
        <div class="col-lg-6">
            <section class="panel">
                <div class="layui-collapse ">
                    <div class="layui-colla-item ">
                        <h2 class="layui-colla-title gechat-ucenter-collapse">群组信息</h2>
                        <div class="layui-colla-content layui-show panel-body">
                            <table class="table table-hover personal-task">
                                <tbody>
                                <tr>
                                    <td>
                                        <div class="layui-form-item" style="padding-top: 20px">
                                            <label for="user_name" class="layui-form-label">昵称</label>
                                            <div class="layui-input-inline">
                                                <input type="text" id="user_name" name="user_name" autocomplete="off" class="layui-input group-info-change" data-type="group-name" placeholder="群组名称" value="{{$groupInfo->group_name}}" >
                                                <input type="hidden" id="old-group-name" value="{{$groupInfo->group_name}}">
                                            </div>
                                            <input type="hidden" id="user_avatar" name="user_avatar" value=""/>
                                            <div class="" style="position: absolute;right: 20px;top: 45px;">
                                                <img id="ge_avatar_upload" src="{{$groupInfo->group_avatar}}" width="118px" height="110px" class="">
                                                <div class="site-demo-upbar">
                                                    <button type="button" class="layui-btn" id="ge_group_upload">
                                                        <i class="layui-icon">&#xe67c;</i>修改头像
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-form-item" style="margin-bottom: 20px">
                                            <label for="user_name" class="layui-form-label">群号</label>
                                            <div class="layui-input-inline">
                                                <input type="text" id="user_name" disabled name="user_name" autocomplete="off" class="layui-input" value="{{$groupInfo->group_num}}">
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        <strong>群主</strong>：  {{$uinfo->user_name}}
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>管理员</strong>： {{$uinfo->user_name}}
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="float: left;width: 10%;margin-top: 10px;" >
                                            <span>群简介:</span>
                                        </div>
                                        <div style="float: left;width: 85%">
                                            <input class="layui-layim-remark group-info-change" data-type="group-profile" placeholder="群组简介" value="{{$groupInfo->group_profile}}">
                                            <input type="hidden" id="old-group-profile" value="{{$groupInfo->group_profile}}">
                                        </div>

                                    </td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            <!-- 网站信息统计｛SEO数据统计｝ -->
            <section class="panel">
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
            </section>
        </div>
        <div class="col-lg-6">
            <!-- 快捷操作 -->
            <section class="panel">
                <div class="layui-collapse ">
                    <div class="layui-colla-item ">
                        <h2 class="layui-colla-title gechat-ucenter-collapse">快捷操作</h2>
                        <div class="layui-colla-content layui-show">
                            <table class="table table-hover personal-task shortcut">
                                <tr>
                                    <td>
                                        <div class="quick-operation">
                                            <div class="layui-btn-container">
                                                <button class="layui-btn">群头衔</button>
                                                <button class="layui-btn">群机器人</button>
                                                <button class="layui-btn">匿名聊天</button>
                                                <button class="layui-btn">群内禁言</button>
                                                <button class="layui-btn">一键批准进群</button>
                                                <button class="layui-btn  layui-btn-normal" id="destroyGroup">解散群</button>
                                                <button class="layui-btn">转让群</button>
                                            </div>
                                        </div>
                                        <br>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            <!-- 系统公告 -->
            <section class="panel">
                <div class="layui-collapse ">
                    <div class="layui-colla-item ">
                        <h2 class="layui-colla-title gechat-ucenter-collapse">群公告</h2>
                        <div class="layui-colla-content layui-show">
                            <table class="table table-hover personal-task shortcut">
                                <tr>
                                    <td style="text-align: left">
                                        <div class="c1">公告一</div>
                                        <div class="c2">公告二</div>
                                        <div class="c2">暂未开放</div>
                                        <br>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 最新文章 -->
            <section class="panel">
                <div class="layui-collapse ">
                    <div class="layui-colla-item ">
                        <h2 class="layui-colla-title gechat-ucenter-collapse">加群请求</h2>
                        <div class="layui-colla-content layui-show panel-body">
                            <table class="table table-hover personal-task">
                                <tbody>
                                <tr>
                                    <td class="layui-col-md2">请求人</td>
                                    <td>
                                        <a href="#" target="_blank" class="layui-col-md4">附言</a>
                                    </td>
                                    <td class="layui-col-md4">
                                        时间
                                    </td>
                                    <td class="layui-col-md2">
                                        操作
                                    </td>
                                </tr>
                                <tr>
                                    <td>小可爱</td>
                                    <td>
                                        <a href="#" target="_blank">进群找对象，么么哒~</a>
                                    </td>
                                    <td class="">{{date("Y-m-d H:i:s",time())}}</td>
                                    <td class="" style="color: red">
                                        已拒绝
                                    </td>
                                </tr>
                                <tr>
                                    <td>暂未开放</td>
                                    <td>
                                        <a href="#" target="_blank">敬请期待</a>
                                    </td>
                                    <td class="">{{date("Y-m-d H:i:s",time())}}</td>
                                    <td class="" style="color: red">
                                        。。。
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>
<script type="text/javascript" src="{{URL::asset('layui/layui.js')}}"></script>
<script type="text/javascript">
    var change_group_info_url = "{{URL::asset('gechat/chatuser/changeGroupInfo')}}";
    var destroy_chatGroup_url = "{{URL::asset('gechat/chatuser/destroyChatGroup')}}";
    var gechat_url_init ="{{URL::asset('gechat/chatuser/init')}}";
    var get_chatGroupJson_url = "{{URL::asset('gechat/chatuser/getChatGroupJSON')}}";
    var up_avatar_url = "{{URL::asset('gechat/chatuser/upavatar')}}";
    var TOKEN = "{{csrf_token()}}";
    var groupID = "{{$groupInfo->id}}";
    layui.use(['layim', 'jquery','element','upload'], function (layim) {
        var element = layui.element
            ,upload = layui.upload
            ,$ = layui.jquery;
        $('.group-info-change').blur(function () {
            var oldStr ="#old-"+ $(this).attr('data-type');
            if($(this).val() != $(oldStr).val()){
                $.post(change_group_info_url, {
                    content : $(this).val(),
                    group_id : groupID,
                    type : $(this).attr('data-type'),
                    _token: TOKEN
                },function(res){
                    if(0 == res.code){
                        $(oldStr).val(res.data);
                        layer.msg(res.msg, {time:1500},{icon:6});
                    }else{
                        layer.msg(res.msg, {time:1500},{icon:5});
                    }
                }, 'json');
            }
        });
        var uploadInst = upload.render({
            elem: '#ge_group_upload' //绑定元素
            ,url: up_avatar_url //上传接口
            ,accept: 'image'
            ,size: 521
            ,data: {
                "_token":TOKEN
            }
            ,done: function(res){
                console.log(res.data);
                $.post(change_group_info_url, {
                    content : res.data.src,
                    group_id : groupID,
                    type : 'group-avatar',
                    _token: TOKEN
                },function(res){
                    if(0 == res.code){
                        $("#ge_avatar_upload").attr('src',res.data);
                        layer.msg(res.msg, {time:1500},{icon:6});
                    }else{
                        layer.msg(res.msg, {time:1500},{icon:5});
                    }
                }, 'json');

                //$("#user_avatar").val(res[data][src]);
            }
            ,error: function(){
                //请求异常回调
            }
        });
        //解散群
        $('#destroyGroup').click(function () {
            layer.confirm('确定解散该群组？', {
                btn: ['确定', '取消'],
                title: '友情提示',
                closeBtn: 0,
                icon: 3
            }, function(index){
                $.post(destroy_chatGroup_url, {
                    group_id :groupID
                    ,type : 'group'
                    ,_token : TOKEN
                }, function (res) {
                    if(res.code == 0){
                        $.post(gechat_url_init, {
                            type: 'desChatGroup'
                            ,to_id : groupID
                            ,_token : TOKEN
                        }, function (res){
                            if(res.code == 0){
                                layer.msg(res.msg, {icon: 6});
                            }
                            else{
                                layer.msg(res.msg, {icon: 5});
                            }
                        }, 'json');
                    }
                    else{
                        layer.msg(res.msg, {icon: 5});
                    }
                }, 'json');
            });
        });
    });
</script>
<script type="text/javascript" src="{{URL::asset('org/larry-admin/jsplug/echarts.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('org/larry-admin/js/main.js')}}"></script>
</body>
</html>