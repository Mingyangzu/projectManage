<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title> 拓美管理系统 - @yield('title')</title>
  <link rel="stylesheet" href="/layui/css/layui.css">
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    @include('Manage.layouts.header')
    
    @include('Manage.layouts.left')
    
    @section('sidebar')
            这是主布局的侧边栏。
        @show
  
  
  <div class="layui-body" style='margin: 10px 20px;'>
    <!-- 内容主体区域 -->
    @yield('content')
  </div>
  @include('Manage.layouts.footer')
</div>

<script src="/layui/layui.js"></script>
<script>
//JavaScript代码区域
layui.use('element', function(){
  var element = layui.element;
  
});
</script>
</body>
</html>