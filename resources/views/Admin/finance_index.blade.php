@include('Admin.header')
@include('Admin.finance_modal')
<form class="form-horizontal" action="/finance_index" method="get">
    <div class="form-group">
        <div class="col-xs-2">
            <input type="text" class="form-control" placeholder="请输入合同名字" name="select_contract_name" value="@if(isset($arr_post_data['select_contract_name'])){{$arr_post_data['select_contract_name']}}@endif" >
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control" placeholder="请输入项目名字" name="select_project_name" value="@if(isset($arr_post_data['select_project_name'])){{$arr_post_data['select_project_name']}}@endif" >
        </div>
        <div class="col-xs-2">
            <select class="form-control" name="select_customer_admin">
                <option value=0>请选择业务员</option>
                @foreach($arr_admin_list as $v)
                    <option value="{{$v->id}}"@if(isset($arr_post_data['select_customer_admin']) && $arr_post_data['select_customer_admin']==$v->id) selected @endif>{{$v->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control" placeholder="请输入合同金额 单位:万元" name="select_contract_money" value="@if(isset($arr_post_data['select_contract_money'])){{$arr_post_data['select_contract_money']}}@endif" >
        </div>
        <div class="col-xs-2">
            <select class="form-control" name="select_collection_name">
                <option value=100>请选择收款摘要</option>
                @foreach($connection_records_name as $k=>$v)
                    <option value="{{$k}}"@if(isset($arr_post_data['select_collection_name']) && $arr_post_data['select_collection_name']==$k) selected @endif>{{$v}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xs-2">
            <select class="form-control" name="select_collection_status">
                <option value=2>请选择本次付款状态</option>
                <option value="1" @if(isset($arr_post_data['select_collection_status']) && $arr_post_data['select_collection_status']==1) selected @endif>已付款</option>
                <option value="0" @if(isset($arr_post_data['select_collection_status']) && $arr_post_data['select_collection_status']==0) selected @endif>未付款</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-2">
            <select class="form-control" name="select_pay_method">
                <option value=100>请选择本次付款方式</option>
                @foreach($pay_method as $k=>$v)
                    <option value="{{$k}}"@if(isset($arr_post_data['select_pay_method']) && $arr_post_data['select_pay_method']==$k) selected @endif>{{$v}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control datetimepicker" placeholder="请选择支付时间" name="select_pay_time" readonly value="@if(isset($arr_post_data['select_pay_time'])){{$arr_post_data['select_pay_time']}}@endif" >
        </div>
        <div class="col-xs-2">
            <button type="submit" class="btn btn-primary" id='sub_select'>查询</button>
            <a href="/finance_index"><button type="button" class="btn btn-primary">重置</button></a>
        </div>
        <div class="col-xs-2" style="float: right;">
            <button type="button" class="btn btn-primary" id="add_project">添加收款记录</button>
        </div>
    </div>
</form>
<div class="alert alert-info" role="alert">符合条件的一共有{{$collection_records_rows}}条数据</div>

<div class="progress" id="progress" data-num="1">
</div>

<table class="table table-hover">
    <tr>
        <th>ID</th>
        <th width="12%">项目名</th>
        <th width="12%">合同名</th>
        <th>收款摘要</th>
        <th>本次收款状态</th>
        <th>合同金额</th>
        <th>本次收款金额</th>
        <th>业务员</th>
        <th>支付方式</th>
        <th>支付时间</th>
        <th>创建时间</th>
        <th>最后修改时间</th>
        <th>操作</th>
    </tr>

    @foreach($arr_collection_records as $k=>$v)
    <tr>
        <td>{{$k+1}}</td>
        <td>{{$v['project_name']}}</td>
        <td>{{$v['contract_name']}}</td>
        <td>{{$connection_records_name[$v['name']]}}</td>
        <td>{{$collection_status[$v['status']]}}</td>
        <td>{{$v['contract_money']}}元</td>
        <td>{{$v['pay_money']}}元</td>
        <td>{{$v['admin_name']}}</td>
        <td>{{$pay_method[$v['pay_method']]}}</td>
        <td>@if($v['pay_time']!=0) @php echo date('Y-m-d',$v['pay_time']); @endphp @else {{$v['pay_time']}} @endif</td>
        <td>@php echo date('Y-m-d',$v['create_time']); @endphp</td>
        <td>@php echo date('Y-m-d',$v['last_time']); @endphp</td>
        <td>
            <button type="button" class="btn btn-info btn-xs edit" data-id={{$v['id']}}>编辑</button>
        </td>
    </tr>
    @endforeach

</table>
{{$arr_collection_records->appends($arr_post_data)->withPath('finance_index')->onEachSide(1)->links()}}
@include('Admin.footer')