<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">
    <title>后台管理模板</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">

    <link rel="stylesheet" type="text/css" href="{{URL::asset('layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('org/beginner-admin/css/global.css')}}" media="all">
    <link rel="stylesheet" type="text/css" href="http://www.jq22.com/jquery/font-awesome.4.6.0.css">

</head>

<body>
<div class="layui-layout layui-layout-admin">
    <div class="layui-header header header-demo">
        <div class="layui-main">
            <div class="admin-login-box">
                <a class="logo" style="left: 0;" href="#">
                    <span style="font-size: 22px;">BeginnerAdmin</span>
                </a>
                <div class="admin-side-toggle">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </div>
            </div>
            <ul class="layui-nav admin-header-item">
                <li class="layui-nav-item">
                    <a href="javascript:;">清除缓存</a>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;">浏览网站</a>
                </li>
                <li class="layui-nav-item" id="video1">
                    <a href="javascript:;">视频</a>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;" class="admin-header-user">
                        <img src="images/0.jpg" />
                        <span>beginner</span>
                    </a>
                    <dl class="layui-nav-child">
                        <dd>
                            <a href="javascript:;"><i class="fa fa-user-circle" aria-hidden="true"></i> 个人信息</a>
                        </dd>
                        <dd>
                            <a href="javascript:;"><i class="fa fa-gear" aria-hidden="true"></i> 设置</a>
                        </dd>
                        <dd id="lock">
                            <a href="javascript:;">
                                <i class="fa fa-lock" aria-hidden="true" style="padding-right: 3px;padding-left: 1px;"></i> 锁屏 (Alt+L)
                            </a>
                        </dd>
                        <dd>
                            <a href="login.html"><i class="fa fa-sign-out" aria-hidden="true"></i> 注销</a>
                        </dd>
                    </dl>
                </li>
            </ul>
            <ul class="layui-nav admin-header-item-mobile">
                <li class="layui-nav-item">
                    <a href="login.html"><i class="fa fa-sign-out" aria-hidden="true"></i> 注销</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="layui-side layui-bg-black" id="admin-side">
        <div class="layui-side-scroll" id="admin-navbar-side" lay-filter="side"></div>
    </div>
    <div class="layui-body" style="bottom: 0;border-left: solid 2px #1AA094;" id="admin-body">
        <div class="layui-tab admin-nav-card layui-tab-brief" lay-filter="admin-tab">
            <ul class="layui-tab-title">
                <li class="layui-this">
                    <i class="fa fa-dashboard" aria-hidden="true"></i>
                    <cite>控制面板</cite>
                </li>
            </ul>
            <div class="layui-tab-content" style="min-height: 150px; padding: 5px 0 0 0;">
                <div class="layui-tab-item layui-show">
                    <iframe src="{{URL::asset('admin/login')}}"></iframe>
                </div>
            </div>
        </div>
    </div>
    <div class="layui-footer footer footer-demo" id="admin-footer">
        <div class="layui-main">
            <p>2016 &copy;
                <a href="#">beginner.zhengjinfan.cn</a> LGPL license
            </p>
        </div>
    </div>
    <div class="site-tree-mobile layui-hide">
        <i class="layui-icon">&#xe602;</i>
    </div>
    <div class="site-mobile-shade"></div>

    <!--锁屏模板 start-->
    <script type="text/template" id="lock-temp">
        <div class="admin-header-lock" id="lock-box">
            <div class="admin-header-lock-img">
                <img src="{{$uinfo->avatar}}"/>
            </div>
            <div class="admin-header-lock-name" id="lockUserName">beginner</div>
            <input type="text" class="admin-header-lock-input" value="输入密码解锁.." name="lockPwd" id="lockPwd" />
            <button class="layui-btn layui-btn-small" id="unlock">解锁</button>
        </div>
    </script>
    <!--锁屏模板 end -->

    <script type="text/javascript" src="{{URL::asset('layui/layui.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('org/beginner-admin/js/index.js')}}"></script>
    <script src=""></script>
    <script>
        var navs = [{
            "title": "基本元素",
            "icon": "fa-cubes",
            "spread": true,
            "children": [{
                "title": "按钮",
                "icon": "&#xe641;",
                "href": "{{URL::asset('gechat/button')}}"
            }, {
                "title": "表单",
                "icon": "&#xe63c;",
                "href": "form.html"
            }, {
                "title": "表格",
                "icon": "&#xe63c;",
                "href": "table.html"
            }, {
                "title": "导航",
                "icon": "&#xe609;",
                "href": "nav.html"
            }, {
                "title": "Tab选项卡",
                "icon": "&#xe62a;",
                "href": "tab.html"
            }, {
                "title": "辅助性元素",
                "icon": "&#xe60c;",
                "href": "auxiliar.html"
            }]
        }, {
            "title": "组件",
            "icon": "fa-cogs",
            "spread": false,
            "children": [{
                "title": "Datatable",
                "icon": "fa-table",
                "href": "begtable.html"
            }, {
                "title": "Navbar组件",
                "icon": "fa-navicon",
                "href": "navbar.html"
            }]
        }, {
            "title": "第三方组件",
            "icon": "&#x1002;",
            "spread": false,
            "children": [{
                "title": "iCheck组件",
                "icon": "fa-check-square-o",
                "href": "icheck.html"
            }]
        }, {
            "title": "地址本",
            "icon": "fa-address-book",
            "href": "",
            "spread": false,
            "children": [{
                "title": "Github",
                "icon": "fa-github",
                "href": "https://www.github.com/"
            }, {
                "title": "QQ",
                "icon": "fa-qq",
                "href": "http://www.qq.com/"
            }, {
                "title": "Fly社区",
                "icon": "&#xe609;",
                "href": "http://fly.layui.com/"
            }, {
                "title": "新浪微博",
                "icon": "fa-weibo",
                "href": "http://weibo.com/"
            }]
        }, {
            "title": "这是一级导航",
            "icon": "fa-stop-circle",
            "href": "https://www.baidu.com",
            "spread": false
        }];
        layui.use('layer', function() {
            var $ = layui.jquery,
                layer = layui.layer;
            $('#video1').on('click', function() {
                layer.open({
                    title: 'YouTube',
                    maxmin: true,
                    type: 2,
                    content: 'video.html',
                    area: ['800px', '500px']
                });
            });

        });
    </script>
</div>
</body>

</html>