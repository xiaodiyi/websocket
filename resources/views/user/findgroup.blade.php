<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>查找/添加群组</title>
    <link rel="shortcut icon" href="favicon.ico">
    <!-- 引入 Bootstrap -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="{{URL::asset('font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('css/animate.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('org/city-picker/city-picker.css')}}" media="all">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('css/profile.css')}}" media="all">
</head>
<style>
    .h1, .h2, .h3, h1, h2, h3 {
        margin-top: 20px;
        margin-bottom: 10px;
        font-family: inherit;
        font-weight: 500;
        line-height: 1.1;
        color: inherit;
    }
    .gebox .gebox-content{
        padding: 9px;
        color: #333;
    }
    .gebox .gebox-content .gebox-title h3 {
        margin: 5px 0 2px 4px;
        font-size: 10px;
    }
    .gebox .gebox-content p{
        margin-top: 10px;
        text-indent: 2em;
        float: left;
        font-size: 10px;
        color: red;
        overflow: hidden;
        display: inline-block;
    }
    .gebox .gebox-content img{
        float: left;
        display: inline-block;
        padding: 4px;
        margin-bottom: 0;
        line-height: 1.42857143;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        -webkit-transition: border .2s ease-in-out;
        -o-transition: border .2s ease-in-out;
        transition: border .2s ease-in-out;
    }
</style>
<body class="gray-bg">
<a class="group-refresh" style="display: none;" onclick="javascript:location.replace(location.href);" title="刷新" ></a>
<div class="wrapper wrapper-content animated fadeInRight">

    <div class="col-sm-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="{{$assign['person_status']}}"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 找人</a>
                </li>
                <li class="{{$assign['group_status']}}"><a data-toggle="tab" href="#tab-2" aria-expanded="false">找群</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane {{$assign['person_status']}}">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="ibox float-e-margins">
                                <div class="ibox-content">
                                    <form class="form-horizontal m-t layui-form" id="userForm" method="post" action="{{URL::asset('gechat/chatuser/findgroup')}}">
                                        {{csrf_field()}}
                                        <input type="hidden" name="type" id="type"/><!--城市id-->
                                        <div class="layui-form-item">
                                            <div class=" layui-col-md2">
                                                <input type="text" name="form-target-friend" required lay-verify="required" placeholder="请输入账号或者昵称." autocomplete="off" class="layui-input">
                                            </div>
                                            <div class=" layui-col-md2">
                                                <select name="form-sex" lay-verify="">
                                                    <option value="" selected>性别</option>
                                                    <option value="0">不限</option>
                                                    <option value="1">男</option>
                                                    <option value="-1">女</option>
                                                </select>
                                            </div>
                                            <div class="layui-col-md2">
                                                <select name="form-age" lay-verify="">
                                                    <option value="" selected>年龄</option>
                                                    <option value="0">18岁以下</option>
                                                    <option value="1">18~22岁</option>
                                                    <option value="-1">23~26岁</option>
                                                    <option value="-1">27~35岁</option>
                                                    <option value="-1">36~120岁</option>
                                                </select>
                                            </div>
                                            <div style="position: relative;float: left" class="city-selector"><!-- container -->
                                                <input name="form-district" readonly type="text" data-toggle="city-picker" data-responsive="true"  data-simple="true" size="50" value="" placeholder="所在地：中国.">
                                            </div>
                                            <div class="">
                                                    <button class="layui-btn" lay-submit lay-filter="formDemo" onclick="choosePerson()">搜索</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 查询结果 -->
                    <div class="row">
                        <div class="col-sm-3"><label id="s_u_title">查询结果</label></div>
                    </div>
                    <div class="row" id="s_u_data">
                        @if (!empty($assign['resultsF']))
                            @foreach($assign['resultsF'] as $v)
                                <div class="col-sm-3">
                                    <div class="ibox float-e-margins">
                                        <div class="ibox-title">
                                            <h5>{{$v->user_name}}</h5>
                                            <span style="margin-left: 10px"><i class="layui-icon" @if($v->sex == 1)style="color:#7CA3D2" @else style="color:#FDA357" @endif>&#xe612;</i></span>
                                            <span style="margin-left: 10px">{{$v->age}}岁</span>
                                        </div>
                                        <div class="ibox-content">
                                            <div style="margin: 0 auto">
                                                <img src="{{$v->avatar}}" width="50px" height="50px"/>
                                                <span style="font-size: 10px;width:104px;overflow: hidden;display: inline-block">{{$v->area}}</span>
                                            </div>
                                            <div style="margin:10px 5px 0 0;float: left"><button class="btn layui-btn-normal" type="button" onclick="look({{$v->id}})">查看</button></div>
                                            <div style="margin:10px 5px 0 0;float: left"><button class="btn layui-btn-warm" type="button" onclick="addFriend({{$v->id}})">加好友</button></div>
                                        </div>
                                        <div style="display: none;">

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <!-- 推荐好友 -->
                    <div class="row">
                        <div class="col-sm-3"><label id="s_u_title">好友推荐</label></div>
                    </div>
                    <div class="row" id="s_u_data">
                        @if (!empty($assign['friends']))
                            @foreach($assign['friends'] as $v)
                                <div class="col-sm-3">
                                    <div class="ibox float-e-margins">
                                        <div class="ibox-title">
                                            <h5>{{$v->user_name}}</h5>
                                            <span style="margin-left: 10px"><i class="layui-icon" @if($v->sex == 1)style="color:#7CA3D2" @else style="color:#FDA357" @endif>&#xe612;</i></span>
                                            <span style="margin-left: 10px">{{$v->age}}岁</span>
                                        </div>
                                        <div class="ibox-content">
                                            <div style="margin: 0 auto">
                                                <img src="{{$v->avatar}}" width="50px" height="50px"/>
                                                <span style="font-size: 10px;width:104px;overflow: hidden;display: inline-block">{{$v->area}}</span>
                                            </div>
                                            <div style="margin:10px 5px 0 0;float: left"><button class="btn " type="button" onclick="look({{$v->id}})">查看</button></div>
                                            <div style="margin:10px 5px 0 0;float: left"><button class="btn " type="button" onclick="addFriend({{$v->id}})">加好友</button></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div id="tab-2" class="tab-pane {{$assign['group_status']}}">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="ibox float-e-margins">
                                <div class="ibox-content">
                                    <form class="form-horizontal m-t" id="commentForm" method="post" action="{{URL::asset('gechat/chatuser/findgroup')}}">
                                        {{csrf_field()}}
                                        <input type="hidden" value="group" name="type">
                                        <div class="form-group">
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="form-target-group" placeholder="输入群组名称" id="search_txt">
                                                    <span class="input-group-btn">
                                                        <button type="submit" class="btn layui-btn" id="find">搜索</button>
                                                    </span>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn layui-btn" id="createGroup">创建群组</button>
                                                    </span>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn layui-btn" id="myGroup">我的群组</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 群组查询结果 -->
                    <div class="row">
                        <div class="col-sm-3"><label id="search_title">查询结果</label></div>
                    </div>
                    <div class="row" id="search_data">
                        @if (!empty($assign['resultsG']))
                            @foreach($assign['resultsG'] as $v)
                                <div class="col-sm-3">
                                    <div class="gebox">
                                        <div class="gebox-content">
                                            <div style="margin: 0 auto">
                                                <img src="{{$v->group_avatar}}" width="100px" height="100px"/>
                                            </div>
                                            <div style="clear: both"></div>
                                            <div class="gebox-title">
                                                <h3>{{$v->group_name}}</h3>
                                            </div>
                                            <div style=""><button class="btn layui-btn-warm btn-addGroup" type="button" onclick="addGroup({{$v->id}})">申请加群</button></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <!-- 最近新建的分组 -->
                    <div class="row">
                        <div class="col-sm-3"><label id="search_title">最近新添加的群组</label></div>
                    </div>
                    <div class="row" id="search_data">
                        @if (!empty($assign['groups']))
                            @foreach($assign['groups'] as $v)
                                <div class="col-sm-3">
                                    <div class="gebox">
                                        <div class="gebox-content">
                                            <div style="margin: 0 auto">
                                                <img src="{{$v->group_avatar}}" width="100px" height="100px"/>
                                            </div>
                                            <div style="clear: both"></div>
                                            <div class="gebox-title">
                                                <h3>{{$v->group_name}}</h3>
                                            </div>
                                            <div style=""><button class="btn btn-normal btn-addGroup" type="button" onclick="addGroup({{$v->id}})">申请加群</button></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div>

</div>
<!-- 选择省市区 -->
<div class="wrapper wrapper-content animated" id="show" style="display: none">
    <div class="row">
        <div class="col-sm-12">
            <select class="form-control m-b" id="p">
                <option value="0" data-id="0">不限区域</option>
                <option value="{$vo.area_name}" data-id="{$vo.id}">{$vo.area_name}</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <select class="form-control m-b" id="c" disabled>
                <option value="0" data-id="0">请选择城市</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <select class="form-control m-b" id="a" disabled>
                <option value="0" data-id="0">请选择区</option>
            </select>
        </div>
    </div>
    <div class="col-sm-4 col-sm-offset-4" style="margin-bottom: 10px">
        <button class="btn btn-primary" type="submit" id="makesure">确认</button>
    </div>
</div>
<input type="hidden" id="pname"/>
<input type="hidden" id="cname"/>
<input type="hidden" id="aname"/>

<script src="{{URL::asset('js/jquery-3.2.1.min.js')}}"></script>
{{--Bootstrap3.3.7--}}
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{URL::asset('layui/layui.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('org/city-picker/city-picker.data.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('org/city-picker/city-picker.js')}}"></script>
<script type="text/javascript">
    var create_group_url = "{{URL::asset('gechat/chatuser/createGroup')}}";
    var my_chatGroup_url = "{{URL::asset('gechat/ucenter')}}";
    var look_friend_url = "{{URL::asset('gechat/chatuser/lookFriend')}}";
    function changeAge(str) {
        $("#age").val(str);
    }
    function changeSex(value){
        $("#sex").val(value);
    }
    function choosePerson(){
        $("#type").val('friend');
    }
    function chooseGroup(){
        $("#type").val('group');
    }
    layui.use(['form','layer'], function(){
        var form = layui.form;
        var layer = layui.layer;
        //提示
        @if(session('status'))
            layer.msg('{{session('status')}}');
        @endif

        //监听提交
        form.on('submit(formDemo)', function(data){
            return true;
        });
        $("#createGroup").click(function () {
            var index = parent.layer.open({
                type: 2
                ,title: '创建群组'
                ,content: create_group_url
                ,maxmin: true
                ,area: ['500px', '500px']
            });
        });
        $("#myGroup").click(function(){
            var index = parent.layer.open({
                type: 2
                ,title: '我的群组'
                ,content: my_chatGroup_url
                ,maxmin: true
                ,area: ['500px', '500px']
            });
            parent.layer.full(index);
        });
    });
    function addFriend(id){
        layui.use(['layer'], function(){
            var layer = layui.layer;
            layer.open({
                type: 2,
                title: '添加好友',
                content: "{{URL::asset('gechat/chatuser/addfriend')}}"+"/"+id,
                area: ['500px', '300px']
            });
        });
    }
    function addGroup(id){
        layui.use(['layer'], function(){
            var layer = layui.layer;
            layer.open({
                type: 2,
                title: '加群',
                area: ['500px', '300px'],
                content: "{{URL::asset('gechat/chatuser/addGroup')}}"+"/"+id
            });
        });
    }
    function look(id){
        layui.use(['layer'], function(){
            parent.layer.open({
                type: 2
                ,title: '好友资料'
                ,maxmin: true
                ,content: look_friend_url+"/"+id+""
                ,area: ['500px', '700px']
                ,skin: ''
            });
        });
    }
</script>
</body>
</html>