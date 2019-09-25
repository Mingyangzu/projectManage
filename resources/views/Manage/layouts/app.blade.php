<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title> 拓美管理系统 - @yield('title')</title>
        <link rel="stylesheet" href="/layui/css/layui.css" media="all">
        <link rel="stylesheet" href="/layui/css/formSelects-v4.css" />
        <script src="/layui/layui.js"></script>
        <script src="/layui/formSelects-v4.js" type="text/javascript" charset="utf-8"></script>
    </head>
    <body class="layui-layout-body">
        <div class="layui-layout layui-layout-admin">
            @include('Manage.layouts.header')

            @include('Manage.layouts.left')

            @section('sidebar')
            @show

            <div class="layui-body" style='margin: 10px 20px;'>
                <!-- 内容主体区域 -->
                @yield('content')
            </div>
            @include('Manage.layouts.footer')
        </div>


        <script>
        //JavaScript代码区域
            layui.use(['element', 'jquery'], function () {
                var element = layui.element, $ = layui.jquery, nowpagename = '';

//                var pathnameonelen = location.pathname.indexOf('/');
//                nowpagename = pathnameonelen > 1 ? location.pathname.substring(1, pathnameonelen) : location.pathname.substring(1);
                
                nowpagename = location.pathname.replace(/\//g, '');
                $('#' + nowpagename).addClass('layui-this');
                $('#' + nowpagename).parent().parent('li').addClass('layui-nav-itemed');

                element.on('nav(leftmenu)', function (elem) {
//              debugger;
                    elem.parent().siblings().removeClass('layui-nav-itemed');

                });

            });
        </script>
    </body>
</html>