<nav class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">
        <ul class="nav navbar-nav">
            <li><img src="{{URL::asset('images/top_logo.png')}}"></li>
            <li>
                <a class="navbar-brand"></a>
                <a class="navbar-brand"></a>
            </li>
        </ul>
        <ul class="nav navbar-nav">
                @foreach($menu_list as $v)
                    @if($arr_login_admin['is_super']==1 || in_array($v->link,$arr_have_power))
                        @if($v->pid==0)
                            <li class="dropdown show_active_class">
                                <a href="/{{$v->link}}" class="dropdown-toggle navbar-brand" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{$v->name}}<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    @foreach($menu_list as $vv)
                                        @if($arr_login_admin['is_super']==1 || in_array($vv->link,$arr_have_power))
                                            @if($vv->pid==$v->id)
                                                <li class="@if($curr_route_name==$vv->link) active @endif"><a href="/{{$vv->link}}">{{$vv->name}}</a></li>
                                            @endif
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endif
                @endforeach
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">您好,{{$arr_login_admin['name']}}<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li id="modify_password"><a href="javascript:;">修改密码</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="/sign_out">退出系统</a></li>
                </ul>
            </li>
        </ul>
    </div><!-- /.container-fluid -->
</nav>
