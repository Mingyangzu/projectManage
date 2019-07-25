@include('Admin.header')
@include('Admin.menu_cate_modal')
<form class="form-horizontal" action="/menu_cate_list" method="get">
    <div class="form-group">
        <div class="col-xs-2">
            <input type="text" class="form-control" placeholder="请输入分类名" name="select_menu_cate_name" value="@if(isset($arr_post_data['select_menu_cate_name'])){{$arr_post_data['select_menu_cate_name']}}@endif" >
        </div>
        <div class="col-xs-2">
            <select class="form-control" name="select_menu_cate_status">
                <option value=2>请选择分类状态</option>
                @foreach($menu_status as $k=>$v)
                <option value="{{$k}}" @if(isset($arr_post_data['select_menu_cate_status']) && $arr_post_data['select_menu_cate_status']==$k) selected @endif>{{$v}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xs-2">
            <button type="submit" class="btn btn-primary" id='sub_select'>查询</button>
            <a href="/menu_cate_list"><button type="button" class="btn btn-primary">重置</button></a>
        </div>
        <div class="col-xs-2" style="float: right">
            <button type="button" class="btn btn-primary" id="add_project">添加导航</button>
        </div>
    </div>
</form>
<div class="alert alert-info" role="alert">符合条件的一共有{{$menu_cate_rows}}条数据</div>

<div class="progress" id="progress" data-num="1">
</div>

<table class="table table-hover">
    <tr>
        <th>ID</th>
        <th>名字</th>
        <th>状态</th>
        <th>描述</th>
        <th>创建时间</th>
        <th>最后修改时间</th>
        <th>操作</th>
    </tr>

    @foreach($arr_cate_menu as $k=>$v)
    <tr>
        <td>{{$k+1}}</td>
        <td>{{$v['name']}}</td>
        <td>{{$menu_status[$v['status']]}}</td>
        <td>{{$v['describe']}}</td>
        <td>@php echo date('Y-m-d',$v['create_time']); @endphp</td>
        <td>@php echo date('Y-m-d',$v['last_time']); @endphp</td>
        <td>
            <button type="button" class="btn btn-info btn-xs edit" data-id={{$v['id']}}>编辑</button>
        </td>
    </tr>
    @endforeach

</table>
{{$arr_cate_menu->appends($arr_post_data)->withPath('menu_cate_list')->onEachSide(1)->links()}}
@include('Admin.footer')