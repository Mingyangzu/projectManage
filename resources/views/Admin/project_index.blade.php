@include('Admin.header')
@include('Admin.project_modal')
<form class="form-horizontal" action="/project_index" method="get">
    <div class="form-group">
        <div class="col-xs-2">
            <input type="text" class="form-control" placeholder="请输入项目经理姓名" name="select_admin_name" value="@if(isset($arr_post_data['select_admin_name'])){{$arr_post_data['select_admin_name']}}@endif" >
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control" placeholder="请输入项目名称" name="select_project_name" value="@if(isset($arr_post_data['select_project_name'])){{$arr_post_data['select_project_name']}}@endif" >
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control" placeholder="请输入项目周期" name="select_project_cycle" value="@if(isset($arr_post_data['select_project_cycle'])){{$arr_post_data['select_project_cycle']}}@endif" >
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control" placeholder="客户电话或座机或微信" name="select_project_customer" value="@if(isset($arr_post_data['select_project_customer'])){{$arr_post_data['select_project_customer']}}@endif" >
        </div>
        <div class="col-xs-2">
            <select class="form-control" name="select_project_type">
                <option value=0>请选择项目类型</option>
                @foreach($arr_type as $v)
                    <option value="{{$v->id}}" @if(isset($arr_post_data['select_project_type']) && $arr_post_data['select_project_type']==$v->id) selected @endif>{{$v->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xs-2">
            <select class="form-control" name="select_project_step">
                <option value=0>请选择项目进度</option>
                @foreach($arr_step as $v)
                    <option value="{{$v->id}}" @if(isset($arr_post_data['select_project_step']) && $arr_post_data['select_project_step']==$v->id) selected @endif>{{$v->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-2">
            <select class="form-control" name="select_pay_status">
                <option value=2>请选择财务状态</option>
                <option value="0" @if(isset($arr_post_data['select_pay_status']) && $arr_post_data['select_pay_status']==0) selected @endif>收款阶段</option>
                <option value="1" @if(isset($arr_post_data['select_pay_status']) && $arr_post_data['select_pay_status']==1) selected @endif>已收款</option>
            </select>
        </div>
        <div class="col-xs-2">
            <select class="form-control" name="select_project_status">
                <option value=2>请选择项目状态</option>
                <option value="1" @if(isset($arr_post_data['select_project_status']) && $arr_post_data['select_project_status']==1) selected @endif>有效</option>
                <option value="0" @if(isset($arr_post_data['select_project_status']) && $arr_post_data['select_project_status']==0) selected @endif>无效</option>
            </select>
        </div>
        <div class="col-xs-2">
            <select class="form-control" name="select_project_is_bid">
                <option value=2>是否为投标项目</option>
                <option value="0" @if(isset($arr_post_data['select_project_is_bid']) && $arr_post_data['select_project_is_bid']==0) selected @endif>否</option>
                <option value="1" @if(isset($arr_post_data['select_pay_status']) && $arr_post_data['select_project_is_bid']==1) selected @endif>是</option>
            </select>
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control datetimepicker" placeholder="请选择创建时间开始" name="select_create_time_start" readonly value="@if(isset($arr_post_data['select_create_time_start'])){{$arr_post_data['select_create_time_start']}}@endif" >
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control datetimepicker" placeholder="请选择创建时间结束" name="select_create_time_end" readonly value="@if(isset($arr_post_data['select_create_time_end'])){{$arr_post_data['select_create_time_end']}}@endif" >
        </div>
        <div class="col-xs-2">
            <button type="submit" class="btn btn-primary" id='sub_select'>查询</button>
            <a href="/project_index"><button type="button" class="btn btn-primary">重置</button></a>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-1" style="float:right">
            <button type="button" class="btn btn-primary" id="add_project">添加项目</button>
        </div>
    </div>
</form>
<div class="alert alert-info" role="alert">符合条件的一共有{{$project_rows}}条数据</div>

<div class="progress" id="progress" data-num="1">
</div>

<table class="table table-hover">
    <tr>
        <th>ID</th>
        <th width="10%">项目名</th>
        <th>项目状态</th>
        <th>是否投标</th>
        <th>项目备注</th>
        <th>项目进度/类型</th>
        <th>业务员</th>
        <th>项目经理</th>
        <th>财务状态</th>
        <th width="10%">所属客户</th>
        <th>客户电话</th>
        <th>创建时间/修改时间</th>
        <th>操作</th>
    </tr>

    @foreach($arr_project as $k=>$v)
    <tr>
        <td>{{$k+1}}</td>
        <td>{{$v['name']}}</td>
        <td>{{$project_status[$v['status']]}}</td>
        <td>{{$project_is_bid[$v['is_bid']]}}</td>
        <td><textarea class="form-control" rows="1" cols="3" disabled>{{$v['remarks']}}</textarea></td>
        <td>
            {{$v['step_name']}}<br />
            {{$v['type_name']}}
        </td>
        <td>{{$v['cus_admin_name']}}</td>
        <td>{{$v['admin_name']}}</td>
        <td>{{$project_pay_status[$v['payment_status']]}}</td>
        <td>{{$v['company_name']}}</td>
        <td>
            {{$v['cus_phone']}}<br />
            {{$v['cus_landline']}}<br />
            {{$v['cus_wechat']}}
        </td>
        <td>
            @php echo date('Y-m-d',$v['create_time']); @endphp<br />
            @php echo date('Y-m-d',$v['last_time']); @endphp
        </td>
        <td>
            <button type="button" class="btn btn-info btn-xs edit" data-id={{$v['id']}}>编辑</button>
            <a class="btn btn-info btn-xs" href="/contract_index?p_id={{$v['id']}}" role="button">查看合同</a>
        </td>
    </tr>
    @endforeach

</table>
{{$arr_project->appends($arr_post_data)->withPath('admin_index')->onEachSide(1)->links()}}
@include('Admin.footer')