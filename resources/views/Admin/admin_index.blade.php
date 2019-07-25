@include('Admin.header')
@include('Admin.admin_modal')
<form class="form-horizontal" action="/admin_index" method="get">
    <div class="form-group">
        <div class="col-xs-2">
            <input type="text" class="form-control" placeholder="请输入管理员姓名" name="select_admin_name" value="@if(isset($arr_post_data['select_admin_name'])){{$arr_post_data['select_admin_name']}}@endif" >
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control" placeholder="请输入管理员邮箱" name="select_admin_email" value="@if(isset($arr_post_data['select_admin_email'])){{$arr_post_data['select_admin_email']}}@endif" >
        </div>
        <div class="col-xs-2">
            <select class="form-control" name="select_admin_status">
                <option value=2>请选择管理员状态</option>
                @foreach($admin_status as $k=>$v)
                <option value="{{$k}}" @if(isset($arr_post_data['select_admin_status']) && $arr_post_data['select_admin_status']==$k) selected @endif>{{$v}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xs-2">
            <select class="form-control" name="select_admin_sex">
                <option value=2>请选择管理员性别</option>
                @foreach($admin_sex as $k=>$v)
                    <option value="{{$k}}" @if(isset($arr_post_data['select_admin_sex']) && $arr_post_data['select_admin_sex']==$k) selected @endif>{{$v}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xs-2">
            <button type="submit" class="btn btn-primary" id='sub_select'>查询</button>
            <a href="/admin_index"><button type="button" class="btn btn-primary">重置</button></a>
        </div>
        <div class="col-xs-2" style="float: right">
            <button type="button" class="btn btn-primary" id="add_project">添加管理员</button>
        </div>
    </div>
</form>
<div class="alert alert-info" role="alert">符合条件的一共有{{$admin_rows}}条数据</div>

<div class="progress" id="progress" data-num="1">
</div>

<table class="table table-hover">
    <tr>
        <th>ID</th>
        <th>名字</th>
        <th>Email</th>
        <th>性别</th>
        <th>手机</th>
        <th width="10%">银行卡/银行</th>
        <th>身份证</th>
        <th>状态</th>
        <th>角色</th>
        <th>
            创建时间<br />
            最后修改时间
        </th>
        <th>操作</th>
    </tr>

    @foreach($arr_admin as $k=>$v)
    <tr>
        <td>{{$k+1}}</td>
        <td>{{$v['name']}}</td>
        <td>{{$v['email']}}</td>
        <td>{{$admin_sex[$v['sex']]}}</td>
        <td>{{$v['phone']}}</td>
        <td>
            {{$v['bank_card']}}<br />
            {{$v['bank_name']}}
        </td>
        <td>{{$v['id_card']}}</td>
        <td>{{$admin_status[$v['status']]}}</td>
        <td>{{$v['role']}}</td>
        <td>
            @php echo date('Y-m-d',$v['create_time']); @endphp<br />
            @php echo date('Y-m-d',$v['last_time']);@endphp
        </td>
        <td>
            <button type="button" class="btn btn-info btn-xs edit" data-id={{$v['id']}}>编辑</button>
        </td>
    </tr>
    @endforeach

</table>
{{$arr_admin->appends($arr_post_data)->withPath('admin_index')->onEachSide(1)->links()}}
@include('Admin.footer')