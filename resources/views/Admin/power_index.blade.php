@include('Admin.header')
@include('Admin.power_modal')
<form class="form-horizontal" action="/power_index" method="get">
    <div class="form-group">
        <div class="col-xs-2">
            <input type="text" class="form-control" placeholder="请输入权限名" name="select_power_name" value="@if(isset($arr_post_data['select_power_name'])){{$arr_post_data['select_power_name']}}@endif" >
        </div>
        <div class="col-xs-2">
            <select class="form-control" name="select_power_status">
                <option value=2>请选择权限状态</option>
                @foreach($power_status as $k=>$v)
                <option value="{{$k}}" @if(isset($arr_post_data['select_power_status']) && $arr_post_data['select_power_status']==$k) selected @endif>{{$v}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xs-2">
            <button type="submit" class="btn btn-primary" id='sub_select'>查询</button>
            <a href="/power_index"><button type="button" class="btn btn-primary">重置</button></a>
        </div>
        <div class="col-xs-2" style="float: right">
            <button type="button" class="btn btn-primary" id="add_project">添加权限</button>
        </div>
    </div>
</form>
<div class="alert alert-info" role="alert">符合条件的一共有{{$power_rows}}条数据</div>

<div class="progress" id="progress" data-num="1">
</div>

<table class="table table-hover">
    <tr>
        <th>ID</th>
        <th>名字</th>
        <th>链接</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>最后修改时间</th>
        <th>操作</th>
    </tr>

    @foreach($arr_power as $k=>$v)
    <tr>
        <td>{{$k+1}}</td>
        <td>{{$v['name']}}</td>
        <td>{{$v['link']}}</td>
        <td>{{$power_status[$v['status']]}}</td>
        <td>@php echo date('Y-m-d',$v['create_time']); @endphp</td>
        <td>@php echo date('Y-m-d',$v['last_time']); @endphp</td>
        <td>
            <button type="button" class="btn btn-info btn-xs edit" data-id={{$v['id']}}>编辑</button>
        </td>
    </tr>
    @endforeach

</table>
{{$arr_power->appends($arr_post_data)->withPath('power_index')->onEachSide(1)->links()}}
@include('Admin.footer')