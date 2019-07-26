  
<div class="layui-header">
    <div class="layui-logo"><img src="{{URL::asset('images/top_logo.png')}}"></div>
    <!-- 头部区域（可配合layui已有的水平导航） -->
    <ul class="layui-nav layui-layout-left">
      <li class="layui-nav-item"><a href="/welcome">控制台</a></li>
<!--      <li class="layui-nav-item"><a href="">商品管理</a></li>
      <li class="layui-nav-item"><a href="">用户</a></li>
      <li class="layui-nav-item">
        <a href="javascript:;">其它系统</a>
        <dl class="layui-nav-child">
          <dd><a href="">邮件管理</a></dd>
          <dd><a href="">消息管理</a></dd>
          <dd><a href="">授权管理</a></dd>
        </dl>
      </li>-->
    </ul>
    <ul class="layui-nav layui-layout-right">
      <li class="layui-nav-item">
        <a href="javascript:;">
          欢迎, 
          {{$arr_login_admin['name']}}
        </a>
        <dl class="layui-nav-child">
          <dd id="modify_password"><a href="javascript:;">修改密码</a></dd>
          <dd> <a href="/sign_out">退出系统</a></dd>
        </dl>
      </li>
      <!--<li class="layui-nav-item">  </li>-->
    </ul>
  </div>
