<div class="layui-side layui-bg-black">    
    <div class="layui-side-scroll">
        <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
        <ul class="layui-nav layui-nav-tree"  lay-filter="test">A
            @foreach($menu_list as $v)
            @if($arr_login_admin['is_super']==1 || in_array($v->link,$arr_have_power))
            @if($v->pid==0)
            <li class="layui-nav-item @if($curr_route_name==$v->link) layui-nav-itemed @endif ">
                @if($v->pid == 0)
                <a class="" href="javascript:;">{{$v->name}}</a>
                @else
                <a class="" href="/{{$v->link}}">{{$v->name}}</a>
                @endif
                <dl class="layui-nav-child">
                    @foreach($menu_list as $vv)
                    @if($arr_login_admin['is_super']==1 || in_array($vv->link,$arr_have_power))
                    @if($vv->pid==$v->id)
                    <dd><a href="/{{$vv->link}}"> {{$vv->name}} </a></dd>
                    @endif
                    @endif
                    @endforeach
                </dl>
            </li>
            @endif
            @endif
            @endforeach

<!--            <li class="layui-nav-item">
                <a href="javascript:;">解决方案</a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:;">列表一</a></dd>
                    <dd><a href="javascript:;">列表二</a></dd>
                    <dd><a href="">超链接</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item"><a href="">云市场</a></li>
            <li class="layui-nav-item"><a href="">发布商品</a></li>-->
        </ul>
    </div>
</div>
