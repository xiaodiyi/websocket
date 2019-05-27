<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn">
<head>
    <title>极客凌云─首页</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="zh-CN" />
    <meta name="Author" content="黄宇轩" />
    <meta name="Copyright" content="极客凌云" />
    <meta name="keywords" content="极客,博客,编程,平台,简洁,美观,大气" />
    <meta name="description" content="极客凌云─集简洁、大气、唯美、时尚于一身的智能云博客发布平台，加入我们！感受极客的精神，分享极客的生活，发现极客的信条，领略极客的风采，你最终会爱上这个平台！" />
    <link rel="shortcut icon" href="{{URL::asset('images/icons/favicon.ico')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no">

    <link rel="stylesheet" href="{{URL::asset('layui/css/layui.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/style.css')}}">

    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Favicon and touch icons -->
    <link rel="shortcut icon" href="{{URL::asset('images/icons/favicon.png')}}">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{URL::asset('images/icons/apple-touch-icon-144-precomposed.png')}}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{URL::asset('images/icons/apple-touch-icon-114-precomposed.png')}}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{URL::asset('images/icons/apple-touch-icon-72-precomposed.png')}}">
    <link rel="apple-touch-icon-precomposed" href="{{URL::asset('images/icons/apple-touch-icon-57-precomposed.png')}}">
</head>
<body>
<div class="layui-header header header-demo">
        <div class="layui-container">
            <div class="layui-row"  style="background-color: #393D49">
                    <a class="logo" id="jkly-brand2" style="display: none">G<span class="layui-badge-dot"></span></a>
                    <a class="logo" id="jkly-brand">GeekClouds <span class="layui-badge-dot"></span></a>
                    <ul class="layui-nav" id="top-nav-ul" lay-filter="" style="float: right!important;">
                        <li class="layui-nav-item" id="index-item1">
                            <a href="">首页</a>
                        </li>
                        <li id="index-item2" class="layui-nav-item" style="display: none">
                            <a href="">首页</a>
                            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                                <dd><a href="" >社区</a></dd>
                                <dd><a href="">下载</a></dd>
                                <dd><a href="">关于我们</a></dd>
                            </dl>
                        </li>
                        <li class="layui-nav-item" id="blog-item1">
                            <a href="">博客</a>
                            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                                <dd><a href="" >编程</a></dd>
                                <dd><a href="">图文</a></dd>
                                <dd><a href="">新鲜事</a></dd>
                            </dl>
                        </li>
                        <li class="layui-nav-item2" style="display: none">
                            <a href="">博客</a>
                            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                                <dd><a href="" >编程</a></dd>
                                <dd><a href="">图文</a></dd>
                                <dd><a href="">新鲜事</a></dd>
                            </dl>
                        </li>
                        <li class="layui-nav-item" id="social-item"><a href="">社区</a></li>
                        <li class="layui-nav-item layui-this" id="download-item"><a href="">下载</a></li>
                        <li class="layui-nav-item" id="about-item"><a href="">关于我们</a></li>
                        <li class="layui-nav-item">
                            <a href=""><img src="http://t.cn/RCzsdCq" class="layui-nav-img">我</a>
                            <dl class="layui-nav-child">
                                <dd><a href="javascript:;">我的博客</a></dd>
                                <dd><a href="javascript:;">我的消息<span class="layui-badge-dot"></span></a></dd>
                                <dd><a href="javascript:;">修改信息</a></dd>
                                <dd><a href="javascript:;">隐私管理</a></dd>
                                <dd><a href="javascript:;">退了</a></dd>
                            </dl>
                        </li>
                    </ul>
            </div>
        </div>
</div>
<div class="jkly-user-logo" id="jkly-user-logo">
    <div class="layui-container">
        <div class="layui-row">
            <div class="ulogo title">
                <h1><a href=""><span>刘三金的博客</span><br><small><p></p></small></a></h1>
            </div>
        </div>

    </div>
</div>
<div class="jkly-user-carousel" id="jkly-user-carousel">
    <div class="layui-container">
        <div class="layui-row">
            <div class="layui-carousel" id="carousel">
                <div carousel-item>
                    <div><img src="{{URL::asset('images/gallery/medium/example1.jpg')}}"></div>
                    <div><img src="{{URL::asset('images/gallery/medium/example2.jpg')}}"></div>
                    <div><img src="{{URL::asset('images/gallery/medium/example3.jpg')}}"></div>
                    <div><img src="{{URL::asset('images/gallery/medium/example2.jpg')}}"></div>
                    <div><img src="{{URL::asset('images/gallery/medium/example1.jpg')}}"></div>
                </div>
            </div>
            <!-- 条目中可以是任意内容，如：<img src=""> -->
        </div>
    </div>
</div>
<div class="jkly-blog-articles" id="jkly-blog-articles">
    <div class="layui-container">
        <div class="layui-row">
            <div class="layui-col-md9">
                <div class="jkly-blog-content-left">
                        {{--开始输出文章--}}
                        <div class="mask grid post_box" style="min-height: 300px">
                            <figure style="min-height: 200px;">
                                <div class="header">
                                    <h2 class="page-header"><a href="">什么是响应式web设计？</a></h2>
                                    <div class="tag"><strong>Tags:</strong><a href="#">编程</a></div>
                                    <span class="posted_date">
                                        <strong></strong>
                                                </span>
                                </div>
                                <div class="pb_right" >
                                    <img src="{{URL::asset('images/gallery/thumbs/gal1.jpg')}}" class="gk-image-responsive ">
                                    <p><a href=""></a></p>
                                    <div class="comment">
                                        <span class="gk-item">阅读量: <span class="dot-color">()</span></span>
                                        <span class="gk-item">日期: <span class="dot-color">()</span></span>
                                        <span class="gk-item">浏览量: <span class="dot-color">()</span></span>
                                    </div>
                                </div>
                                <figcaption>
                                    <h5>作者:</h5>
                                    <a data-toggle="modal" href="#myModal" class="btn btn-default">查看主页</a>
                                </figcaption>
                                <!-- /figcaption -->
                            </figure>
                            <div class="layui-collapse" style="border-color: rgba(0, 0, 0, 0);;">
                                <div class="layui-colla-item">
                                    <h2 class="layui-colla-title" style="background-color: rgb(255, 255, 255);border-bottom: #d2d2d2 0.5px solid">点我进行更多操作吧！</h2>
                                    <div class="layui-colla-content" >
                                            <div>
                                                <form class="layui-form" action="" id="private-checkbox">
                                                    <div class="layui-form-item">
                                                        <label class="layui-form-label private-lable">私密模式？</label>
                                                        <div class="layui-input-block">
                                                            <input type="checkbox" name="like[dai]" title="私密">
                                                        </div>
                                                    </div>
                                                </form>
                                                <button class="layui-btn layui-btn-normal btn-collect" type="button" value=""><i class="fa fa-heart"></i><span class="text">收藏</span>
                                                </button>
                                            </div>
                                            <div style="clear: both"></div>
                                            <div class= "checkbox-control">
                                                <button class="layui-btn layui-btn-normal btn-thumb" type="button" value=""><i class="glyphicon glyphicon-thumbs-up"></i><span class="text">点赞</span>
                                                </button>
                                                <p class="" id="thumbup_num" style="color: #00CCFF;">
                                                    <i class="glyphicon glyphicon-fire">
                                                    </i>
                                                    (0)人觉得很赞
                                                </p>
                                                <div style="clear: both"></div>
                                            </div>
                                            <div style="clear: both"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {{--开始输出文章--}}
                    <div class="mask grid post_box" style="min-height: 300px">
                        <figure style="min-height: 200px;">
                            <div class="header">
                                <h2 class="page-header"><a href="">什么是响应式web设计？</a></h2>
                                <div class="tag"><strong>Tags:</strong><a href="#">编程</a></div>
                                <span class="posted_date">
                                        <strong></strong>
                                                </span>
                            </div>
                            <div class="pb_right" >
                                <img src="{{URL::asset('images/gallery/thumbs/gal1.jpg')}}" class="gk-image-responsive ">
                                <p><a href=""></a></p>
                                <div class="comment">
                                    <span class="gk-item">阅读量: <span class="dot-color">()</span></span>
                                    <span class="gk-item">日期: <span class="dot-color">()</span></span>
                                    <span class="gk-item">浏览量: <span class="dot-color">()</span></span>
                                </div>
                            </div>
                            <figcaption>
                                <h5>作者:</h5>
                                <a data-toggle="modal" href="#myModal" class="btn btn-default">查看主页</a>
                            </figcaption>
                            <!-- /figcaption -->
                        </figure>
                        <div class="layui-collapse" style="border-color: rgba(0, 0, 0, 0);;">
                            <div class="layui-colla-item">
                                <h2 class="layui-colla-title" style="background-color: rgb(255, 255, 255);border-bottom: #d2d2d2 0.5px solid">点我进行更多操作吧！</h2>
                                <div class="layui-colla-content" >
                                    <div>
                                        <form class="layui-form" action="" id="private-checkbox">
                                            <div class="layui-form-item">
                                                <label class="layui-form-label private-lable">私密模式？</label>
                                                <div class="layui-input-block">
                                                    <input type="checkbox" name="like[dai]" title="私密">
                                                </div>
                                            </div>
                                        </form>
                                        <button class="layui-btn layui-btn-normal btn-collect" type="button" value=""><i class="fa fa-heart"></i><span class="text">收藏</span>
                                        </button>
                                    </div>
                                    <div style="clear: both"></div>
                                    <div class= "checkbox-control">
                                        <button class="layui-btn layui-btn-normal btn-thumb" type="button" value=""><i class="glyphicon glyphicon-thumbs-up"></i><span class="text">点赞</span>
                                        </button>
                                        <p class="" id="thumbup_num" style="color: #00CCFF;">
                                            <i class="glyphicon glyphicon-fire">
                                            </i>
                                            (0)人觉得很赞
                                        </p>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div style="clear: both"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--开始输出文章--}}
                    <div class="mask grid post_box" style="min-height: 300px">
                        <figure style="min-height: 200px;">
                            <div class="header">
                                <h2 class="page-header"><a href="">什么是响应式web设计？</a></h2>
                                <div class="tag"><strong>Tags:</strong><a href="#">编程</a></div>
                                <span class="posted_date">
                                        <strong></strong>
                                                </span>
                            </div>
                            <div class="pb_right" >
                                <img src="{{URL::asset('images/gallery/thumbs/gal1.jpg')}}" class="gk-image-responsive ">
                                <p><a href=""></a></p>
                                <div class="comment">
                                    <span class="gk-item">阅读量: <span class="dot-color">()</span></span>
                                    <span class="gk-item">日期: <span class="dot-color">()</span></span>
                                    <span class="gk-item">浏览量: <span class="dot-color">()</span></span>
                                </div>
                            </div>
                            <figcaption>
                                <h5>作者:</h5>
                                <a data-toggle="modal" href="#myModal" class="btn btn-default">查看主页</a>
                            </figcaption>
                            <!-- /figcaption -->
                        </figure>
                        <div class="layui-collapse" style="border-color: rgba(0, 0, 0, 0);;">
                            <div class="layui-colla-item">
                                <h2 class="layui-colla-title" style="background-color: rgb(255, 255, 255);border-bottom: #d2d2d2 0.5px solid">点我进行更多操作吧！</h2>
                                <div class="layui-colla-content" >
                                    <div>
                                        <form class="layui-form" action="" id="private-checkbox">
                                            <div class="layui-form-item">
                                                <label class="layui-form-label private-lable">私密模式？</label>
                                                <div class="layui-input-block">
                                                    <input type="checkbox" name="like[dai]" title="私密">
                                                </div>
                                            </div>
                                        </form>
                                        <button class="layui-btn layui-btn-normal btn-collect" type="button" value=""><i class="fa fa-heart"></i><span class="text">收藏</span>
                                        </button>
                                    </div>
                                    <div style="clear: both"></div>
                                    <div class= "checkbox-control">
                                        <button class="layui-btn layui-btn-normal btn-thumb" type="button" value=""><i class="glyphicon glyphicon-thumbs-up"></i><span class="text">点赞</span>
                                        </button>
                                        <p class="" id="thumbup_num" style="color: #00CCFF;">
                                            <i class="glyphicon glyphicon-fire">
                                            </i>
                                            (0)人觉得很赞
                                        </p>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div style="clear: both"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--开始输出文章--}}
                    <div class="mask grid post_box" style="min-height: 300px">
                        <figure style="min-height: 200px;">
                            <div class="header">
                                <h2 class="page-header"><a href="">什么是响应式web设计？</a></h2>
                                <div class="tag"><strong>Tags:</strong><a href="#">编程</a></div>
                                <span class="posted_date">
                                        <strong></strong>
                                                </span>
                            </div>
                            <div class="pb_right" >
                                <img src="{{URL::asset('images/gallery/thumbs/gal1.jpg')}}" class="gk-image-responsive ">
                                <p><a href=""></a></p>
                                <div class="comment">
                                    <span class="gk-item">阅读量: <span class="dot-color">()</span></span>
                                    <span class="gk-item">日期: <span class="dot-color">()</span></span>
                                    <span class="gk-item">浏览量: <span class="dot-color">()</span></span>
                                </div>
                            </div>
                            <figcaption>
                                <h5>作者:</h5>
                                <a data-toggle="modal" href="#myModal" class="btn btn-default">查看主页</a>
                            </figcaption>
                            <!-- /figcaption -->
                        </figure>
                        <div class="layui-collapse" style="border-color: rgba(0, 0, 0, 0);;">
                            <div class="layui-colla-item">
                                <h2 class="layui-colla-title" style="background-color: rgb(255, 255, 255);border-bottom: #d2d2d2 0.5px solid">点我进行更多操作吧！</h2>
                                <div class="layui-colla-content" >
                                    <div>
                                        <form class="layui-form" action="" id="private-checkbox">
                                            <div class="layui-form-item">
                                                <label class="layui-form-label private-lable">私密模式？</label>
                                                <div class="layui-input-block">
                                                    <input type="checkbox" name="like[dai]" title="私密">
                                                </div>
                                            </div>
                                        </form>
                                        <button class="layui-btn layui-btn-normal btn-collect" type="button" value=""><i class="fa fa-heart"></i><span class="text">收藏</span>
                                        </button>
                                    </div>
                                    <div style="clear: both"></div>
                                    <div class= "checkbox-control">
                                        <button class="layui-btn layui-btn-normal btn-thumb" type="button" value=""><i class="glyphicon glyphicon-thumbs-up"></i><span class="text">点赞</span>
                                        </button>
                                        <p class="" id="thumbup_num" style="color: #00CCFF;">
                                            <i class="glyphicon glyphicon-fire">
                                            </i>
                                            (0)人觉得很赞
                                        </p>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div style="clear: both"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--开始输出文章--}}
                    <div class="mask grid post_box" style="min-height: 300px">
                        <figure style="min-height: 200px;">
                            <div class="header">
                                <h2 class="page-header"><a href="">什么是响应式web设计？</a></h2>
                                <div class="tag"><strong>Tags:</strong><a href="#">编程</a></div>
                                <span class="posted_date">
                                        <strong></strong>
                                                </span>
                            </div>
                            <div class="pb_right" >
                                <img src="{{URL::asset('images/gallery/thumbs/gal1.jpg')}}" class="gk-image-responsive ">
                                <p><a href=""></a></p>
                                <div class="comment">
                                    <span class="gk-item">阅读量: <span class="dot-color">()</span></span>
                                    <span class="gk-item">日期: <span class="dot-color">()</span></span>
                                    <span class="gk-item">浏览量: <span class="dot-color">()</span></span>
                                </div>
                            </div>
                            <figcaption>
                                <h5>作者:</h5>
                                <a data-toggle="modal" href="#myModal" class="btn btn-default">查看主页</a>
                            </figcaption>
                            <!-- /figcaption -->
                        </figure>
                        <div class="layui-collapse" style="border-color: rgba(0, 0, 0, 0);;">
                            <div class="layui-colla-item">
                                <h2 class="layui-colla-title" style="background-color: rgb(255, 255, 255);border-bottom: #d2d2d2 0.5px solid">点我进行更多操作吧！</h2>
                                <div class="layui-colla-content" >
                                    <div>
                                        <form class="layui-form" action="" id="private-checkbox">
                                            <div class="layui-form-item">
                                                <label class="layui-form-label private-lable">私密模式？</label>
                                                <div class="layui-input-block">
                                                    <input type="checkbox" name="like[dai]" title="私密">
                                                </div>
                                            </div>
                                        </form>
                                        <button class="layui-btn layui-btn-normal btn-collect" type="button" value=""><i class="fa fa-heart"></i><span class="text">收藏</span>
                                        </button>
                                    </div>
                                    <div style="clear: both"></div>
                                    <div class= "checkbox-control">
                                        <button class="layui-btn layui-btn-normal btn-thumb" type="button" value=""><i class="glyphicon glyphicon-thumbs-up"></i><span class="text">点赞</span>
                                        </button>
                                        <p class="" id="thumbup_num" style="color: #00CCFF;">
                                            <i class="glyphicon glyphicon-fire">
                                            </i>
                                            (0)人觉得很赞
                                        </p>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div style="clear: both"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--开始输出文章--}}
                    <div class="mask grid post_box" style="min-height: 300px">
                        <figure style="min-height: 200px;">
                            <div class="header">
                                <h2 class="page-header"><a href="">什么是响应式web设计？</a></h2>
                                <div class="tag"><strong>Tags:</strong><a href="#">编程</a></div>
                                <span class="posted_date">
                                        <strong></strong>
                                                </span>
                            </div>
                            <div class="pb_right" >
                                <img src="{{URL::asset('images/gallery/thumbs/gal1.jpg')}}" class="gk-image-responsive ">
                                <p><a href=""></a></p>
                                <div class="comment">
                                    <span class="gk-item">阅读量: <span class="dot-color">()</span></span>
                                    <span class="gk-item">日期: <span class="dot-color">()</span></span>
                                    <span class="gk-item">浏览量: <span class="dot-color">()</span></span>
                                </div>
                            </div>
                            <figcaption>
                                <h5>作者:</h5>
                                <a data-toggle="modal" href="#myModal" class="btn btn-default">查看主页</a>
                            </figcaption>
                            <!-- /figcaption -->
                        </figure>
                        <div class="layui-collapse" style="border-color: rgba(0, 0, 0, 0);;">
                            <div class="layui-colla-item">
                                <h2 class="layui-colla-title" style="background-color: rgb(255, 255, 255);border-bottom: #d2d2d2 0.5px solid">点我进行更多操作吧！</h2>
                                <div class="layui-colla-content" >
                                    <div>
                                        <form class="layui-form" action="" id="private-checkbox">
                                            <div class="layui-form-item">
                                                <label class="layui-form-label private-lable">私密模式？</label>
                                                <div class="layui-input-block">
                                                    <input type="checkbox" name="like[dai]" title="私密">
                                                </div>
                                            </div>
                                        </form>
                                        <button class="layui-btn layui-btn-normal btn-collect" type="button" value=""><i class="fa fa-heart"></i><span class="text">收藏</span>
                                        </button>
                                    </div>
                                    <div style="clear: both"></div>
                                    <div class= "checkbox-control">
                                        <button class="layui-btn layui-btn-normal btn-thumb" type="button" value=""><i class="glyphicon glyphicon-thumbs-up"></i><span class="text">点赞</span>
                                        </button>
                                        <p class="" id="thumbup_num" style="color: #00CCFF;">
                                            <i class="glyphicon glyphicon-fire">
                                            </i>
                                            (0)人觉得很赞
                                        </p>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div style="clear: both"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="paginate"></div>
                </div>
            </div>
            <div class="layui-col-md3">
                <div class="jkly-blog-content-right">
                    <div class="entry-desc sidebar_box" style="min-height: 300px;margin-bottom: 0px;">
                        <div class="sb_content">
                            <div class="sidebar-user">
                                <img src="{{URL::asset('images/gallery/thumbs/gal1.jpg')}}" class="gk-image-responsive gk-image-circle " style="display:inline-block;">
                                <div style="margin:10px 0 0 10px;">
                                    <h4 style="font-weight: bold"></h4>
                                </div>
                            </div>
                            <div class="sidebar-user">
                                <div class="sidebar-user-2" ><dt>原创</dt><dd class="layui-badge">10</dd></div>
                                <div class="sidebar-user-2" ><dt>粉丝</dt><dd class="layui-badge">13</dd></div>
                                <div class="sidebar-user-2" ><dt>喜欢</dt><dd class="layui-badge">20</dd></div>
                                <div class="sidebar-user-2" ><dt>评论</dt><dd class="layui-badge">45</dd></div>
                            </div>
                            <div class="sidebar-user" style="border-bottom:0px;margin-bottom: 20px;">
                                <div class="sidebar-user-3 pull-left"><span>等级:</span><span class="layui-badge">1024</span></div>
                                <div class="sidebar-user-3 pull-left"><span>访问量:</span><span class="layui-badge">5</span></div>
                                <div class="sidebar-user-3 pull-left"><span>积分:</span><span class="layui-badge">2</span></div>
                                <div class="sidebar-user-3 pull-left"><span>排名:</span><span class="layui-badge">1</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar_box" style="min-height: 300px;margin-bottom: 0px;">
                        <h3>分类</h3>
                        <div class="sb_content">
                            <ul class="sidebar_menu">
                                <li class="active"><a href="#">精美图文<span class="layui-badge">42</span></a></li>
                                <li><a href="#">励志鸡汤<span class="layui-badge">42</span></a></li>
                                <li><a href="#">编程日志<span class="layui-badge">42</span></a></li>
                                <li><a href="#">新鲜事<span class="layui-badge">42</span></a></li>
                                <li><a href="#">新闻讨论<span class="layui-badge">42</span></a></li>
                                <li><a href="#">笑话段子<span class="layui-badge">42</span></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="sidebar_box" id="calendar_box" style="min-height: 300px;margin-bottom: 0px;">
                        <h3 class="">Calendar</h3>
                        <div id='schedule-box' class="">
                            <span id="testView"></span>
                            <div id="test2"></div>
                        </div>
                    </div>
                    <div class=" sidebar_box" style="min-height: 300px;margin-bottom: 0px;box-shadow:none;">
                        <div class="sb_content">
                            <div>
                                <img src="{{URL::asset('images/gallery/thumbs/gal1.jpg')}}" alt="微信" class="img-rounded img-thumbnail img-responsive"/>
                                <p class="fa fa-wechat" style="text-align: left">扫描屏幕上方二维码联系站长</p>
                            </div>
                            <div style="border-top: grey 1px solid;padding: 2px; ">
                                <span class="glyphicon glyphicon-envelope">qianxia_yingliu@outlook.com</span><br>
                                <span class="fa fa-qq pull-left " style="margin: 4px 0 4px 0">2584207910</span>
                                <span class="fa fa-qq pull-right" style="margin: 4px 0 4px 0">1683715501</span>
                            </div>
                            <div style="clear: both"></div>
                            <div style="border-top: grey 1px solid;padding: 2px; ">
                                <p class="pull-left" style="margin: 4px 0 4px 0">吉ICP备16007022号</p>
                                <p class="fa fa-copyright" style="margin: 4px 0 4px 0">极客凌云版权所有</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>
<div class="jk-elem-quote">
    <div class="layui-container">
        <div class="row">
            <p>极客凌云 - 集简约、优雅、时尚于一体的的博客发布平台</p>
        </div>
    </div>
</div>
<footer id="jkly-footer" role="contentinfo" class="">
    <div class="layui-container">
        <div class="row">
            <div class="layui-col-md12">
                <h3 class="footer-heading">最受欢迎的文章</h3>
                <div class="layui-col-md4">
                    <div class="">
                        <div class="img-thumbnail">
                            <a href=""><img  style="height: 150px;" src="{{URL::asset('images/gallery/thumbs/gal1.jpg')}}" class="img-responsive img-thumbnail " alt="图片"></a>
                        </div>
                        <div class="">
                            <h4><a href=""><strong>啊手动阀手动阀手动阀</strong><span class="dot-color">.</span></a></h4>
                            <a href="" class="post-meta"><span class="">年月日</span></a>
                            <a class="fa fa-eye dot-color date-post" style="margin-right: 5%;">浏览次数</a>
                            <div style="clear: both"></div>
                        </div>
                        <div style="display: none"></div>
                    </div>
                </div>
                <div class="layui-col-md4">
                    <div class="">
                        <div class="img-thumbnail">
                            <a href=""><img  style="height: 150px;" src="{{URL::asset('images/gallery/thumbs/gal1.jpg')}}" class="img-responsive img-thumbnail " alt="图片"></a>
                        </div>
                        <div class="">
                            <h4><a href=""><strong>啊手动阀手动阀手动阀</strong><span class="dot-color">.</span></a></h4>
                            <a href="" class="post-meta"><span class="">年月日</span></a>
                            <a class="fa fa-eye dot-color date-post" style="margin-right: 5%;">浏览次数</a>
                            <div style="clear: both"></div>
                        </div>
                        <div style="display: none"></div>
                    </div>
                </div>
                <div class="layui-col-md4">
                    <div class="">
                        <div class="img-thumbnail">
                            <a href=""><img  style="height: 150px;" src="{{URL::asset('images/gallery/thumbs/gal1.jpg')}}" class="img-responsive img-thumbnail " alt="图片"></a>
                        </div>
                        <div class="">
                            <h4><a href=""><strong>啊手动阀手动阀手动阀</strong><span class="dot-color">.</span></a></h4>
                            <a href="" class="post-meta"><span class="">年月日</span></a>
                            <a class="fa fa-eye dot-color date-post" style="margin-right: 5%;">浏览次数</a>
                            <div style="clear: both"></div>
                        </div>
                        <div style="display: none"></div>
                    </div>
                </div>
                <div style="clear: both"></div>
                <div class="row copyright">
                    <div class="col-md-12 text-center">
                        <p>
                            <small class="block fa fa-copyright" style="float: none!important;">挑花人博客 All Rights Reserved.</small>
                            <small class="block"><span class="fa fa-cog fa-spin "></span>作者：<a href="http://www.geekadpt.cn/" target="_blank">黄宇轩 </a> <span class="fa fa-comments-o"></span>智能云博客发表平台: <a href="http://www.geekadpt.cn/" target="_blank">极客凌云</a></small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="{{URL::asset('layui/layui.js')}}" type="text/javascript"></script>
<script>
    layui.config({
        base: '/layui/res/js/modules/' //你存放新模块的目录，注意，不是layui的模块目录
    }).use('index'); //加载入口
    /**
     项目JS主入口
     以依赖layui的layer和form模块为例
     **/
    layui.define(['layer', 'form','element','carousel'], function(exports){
        var layer = layui.layer
            ,form = layui.form
            ,element = layui.element
            ,carousel = layui.carousel;
        //建造实例
        carousel.render({
            elem: '#carousel'
            ,width: '100%' //设置容器宽度
            ,arrow: 'always' //始终显示箭头
            //,anim: 'updown' //切换动画方式
            ,height:'500'
        });
        layer.msg('Hello World');
        exports('index', {}); //注意，这里是模块输出的核心，模块名必须和use时的模块名一致
    });
    layui.use(['laydate','laypage','form'], function(){
        var laydate = layui.laydate
            ,laypage = layui.laypage
            ,form = layui.form;

        //监听提交
        form.on('submit(formDemo)', function(data){
            layer.msg(JSON.stringify(data.field));
            return false;
        });
        //执行一个laydate实例
        laydate.render({
            elem: '#test2'
            ,position: 'static'
            ,change: function(value, date){ //监听日期被切换
                lay('#testView').html(value);
            }
        });
        laypage.render({
            elem: 'paginate' //注意，这里的 test1 是 ID，不用加 # 号
            ,count: 50 //数据总数，从服务端得到
        });
    });
</script>
</body>
</html>