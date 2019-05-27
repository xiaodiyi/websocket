<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>聊天记录</title>

    <link rel="stylesheet" type="text/css" href="{{URL::asset('layui/css/layui.css')}}" media="all">
    <style>
        body .layim-chat-main{height: auto;}
    </style>
</head>
<body>

<div class="layim-chat-main">
    <ul id="LAY_view"></ul>
</div>

<div id="LAY_page" style="margin: 0 10px;"></div>

@verbatim
<textarea title="消息模版" id="LAY_tpl" style="display:none;">
{{# layui.each(d.data, function(index, item){
  if(item.id == parent.layui.layim.cache().mine.id){ }}
    <li class="layim-chat-mine"><div class="layim-chat-user"><img src="{{ item.avatar }}"><cite><i>{{ layui.data.date(item.timestamp) }}</i>{{ item.username }}</cite></div><div class="layim-chat-text">{{ layui.layim.content(item.content) }}</div></li>
    {{# } else { }}
    <li><div class="layim-chat-user"><img src="{{ item.avatar }}"><cite>{{ item.username }}<i>{{ layui.data.date(item.timestamp) }}</i></cite></div><div class="layim-chat-text">{{ layui.layim.content(item.content) }}</div></li>
    {{# }
  }); }}
</textarea>
<div style="width: 100%;height: 70px;"></div>
@endverbatim
<div id="test1" style="position: fixed;bottom: 0;"></div>
<!-- 
上述模版采用了 laytpl 语法，不了解的同学可以去看下文档：http://www.layui.com/doc/modules/laytpl.html

-->


<script type="text/javascript" src="{{URL::asset('layui/layui.js')}}"></script>
<script>
    layui.use(['layim', 'laypage'], function(){
        var layim = layui.layim
            ,layer = layui.layer
            ,laytpl = layui.laytpl
            ,$ = layui.jquery
            ,laypage = layui.laypage;

        //聊天记录的分页此处不做演示，你可以采用laypage，不了解的同学见文档：http://www.layui.com/doc/modules/laypage.html


        //开始请求聊天记录
        //获得URL参数。该窗口url会携带会话id和type，他们是你请求聊天记录的重要凭据
        //实际使用时，下述的res一般是通过Ajax获得，而此处仅仅只是演示数据格式
        {{--var res = JSON.parse(''+'{!! $chatlog !!}'+'');
        console.log(res);
        var html = laytpl(LAY_tpl.value).render({
            data: res.data
        });
        $('#LAY_view').html(html);--}}
        laypage.render({
            elem: 'test1' //注意，这里的 test1 是 ID，不用加 # 号
            ,count: '{{$count}}' //数据总数，从服务端得到
            ,limit: '{{$limit}}'
            ,prev: '<em>←</em>'
            ,next: '<em>→</em>'
            ,layout: ['prev', 'page', 'next', 'skip','count']
            ,jump: function(obj, first) {
                //obj包含了当前分页的所有参数，比如：
                    console.log(obj.curr); //得到当前页，以便向服务端请求对应页的数据。
                    {{--location.href="{{$url}}"+"/"+obj.curr;--}}
                            {{--window.open("{{$url}}"+"/"+obj.curr);--}}
                    @if($type == 'friend')
                        var get_chatlogPages = "{{URL::asset('gechat/chatuser/chatlogs/')}}"+"/"+"{{$ID}}"+"?page="+obj.curr;
                        $.post(get_chatlogPages, {
                             type: 'friend'
                            ,limit : '{{$limit}}'
                            ,_token : '{{csrf_token()}}'
                        }, function (res){
                            console.log(res);
                            if(res.code == 0){
                                var html = laytpl(LAY_tpl.value).render({
                                    data: res.data
                                });
                                $('#LAY_view').html(html);
                            }
                            else{
                                layer.msg('请求服务器信息失败', {icon: 5});
                            }
                        }, 'json');
                    @else
                        var get_chatlogPages = "{{URL::asset('gechat/chatuser/chatlogs/')}}"+"/"+"{{$ID}}"+"?page="+obj.curr;
                        $.post(get_chatlogPages, {
                             type: 'group'
                            ,limit : '{{$limit}}'
                            ,_token : '{{csrf_token()}}'
                        }, function (res){
                            console.log(res);
                            if(res.code == 0){
                                var html = laytpl(LAY_tpl.value).render({
                                    data: res.data
                                });
                                $('#LAY_view').html(html);
                            }
                            else{
                                layer.msg('请求服务器信息失败', {icon: 5});
                            }
                        }, 'json');
                    @endif
                    console.log(obj.limit); //得到每页显示的条数
                }
        });
        // console.log(Request.id)
    });
</script>
</body>
</html>
