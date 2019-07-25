@include('Admin.header')
@include('Admin.expenditure_modal')
<form class="form-horizontal" action="/expenditure_index" method="get">
    <div class="form-group">
        <div class="col-xs-2">
            <select class="form-control" name="select_expenditure_name">
                <option value=0>请选择支出用途</option>
                @foreach($arr_expenditure_name as $k=>$v)
                    <option value="{{$k}}"@if(isset($arr_post_data['select_expenditure_name']) && $arr_post_data['select_expenditure_name']==$k) selected @endif>{{$v}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control" placeholder="请输入支出金额" name="select_expenditure_money" value="@if(isset($arr_post_data['select_expenditure_money'])){{$arr_post_data['select_expenditure_money']}}@endif" >
        </div>
        <div class="col-xs-2">
            <select class="form-control" name="select_expenditure_status">
                <option value=2>请选择本次支出状态</option>
                <option value="1" @if(isset($arr_post_data['select_expenditure_status']) && $arr_post_data['select_expenditure_status']==1) selected @endif>已付款</option>
                <option value="0" @if(isset($arr_post_data['select_expenditure_status']) && $arr_post_data['select_expenditure_status']==0) selected @endif>未付款</option>
            </select>
        </div>
        <div class="col-xs-2">
            <select class="form-control" name="select_pay_method">
                <option value=100>请选择本次付款方式</option>
                @foreach($pay_method as $k=>$v)
                    <option value="{{$k}}"@if(isset($arr_post_data['select_pay_method']) && $arr_post_data['select_pay_method']==$k) selected @endif>{{$v}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xs-2">
            <select class="form-control" name="select_consumer">
                <option value=0>请选择经办人</option>
                @foreach($arr_admin_list as $v)
                    <option value="{{$v->id}}"@if(isset($arr_post_data['select_consumer']) && $arr_post_data['select_consumer']==$v->id) selected @endif>{{$v->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xs-2" style="float: right">
            <button type="button" class="btn btn-primary" id="add_project">添加支出记录</button>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-2">
            <input type="text" class="form-control datetimepicker" placeholder="请选择支付时间开始" name="select_pay_time_start" readonly value="@if(isset($arr_post_data['select_pay_time_start'])){{$arr_post_data['select_pay_time_start']}}@endif" >
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control datetimepicker" placeholder="请选择支付时间结束" name="select_pay_time_end" readonly value="@if(isset($arr_post_data['select_pay_time_end'])){{$arr_post_data['select_pay_time_end']}}@endif" >
        </div>
        <div class="col-xs-2">
            <button type="submit" class="btn btn-primary" id='sub_select'>查询</button>
            <a href="/expenditure_index"><button type="button" class="btn btn-primary">重置</button></a>
        </div>
    </div>
</form>
<div class="alert alert-info" role="alert">符合条件的一共有{{$expenditure_rows}}条数据</div>

<div class="progress" id="progress" data-num="1">
</div>

<table class="table table-hover">
    <tr>
        <th>ID</th>
        <th>用途</th>
        <th>支付时间</th>
        <th>创建时间</th>
        <th>最后修改时间</th>
        <th>操作者</th>
        <th>经办人</th>
        <th>凭证</th>
        <th>支出金额</th>
        <th>支出状态</th>
        <th>支付方式</th>
        <th>备注</th>
        <th>操作</th>
    </tr>

    @foreach($arr_expenditure as $k=>$v)
    <tr>
        <td>{{$k+1}}</td>
        <td>{{$arr_expenditure_name[$v['name']]}}</td>
        <td>@if($v['pay_time']!=0) @php echo date('Y-m-d',$v['pay_time']); @endphp @else {{$v['pay_time']}} @endif</td>
        <td>@php echo date('Y-m-d',$v['create_time']); @endphp</td>
        <td>@php echo date('Y-m-d',$v['last_time']); @endphp</td>
        <td>{{$v['admin_name']}}</td>
        <td>{{$v['consumer_name']}}</td>
        <td><a target="_blank" href="http://{{$v['pay_voucher']}}">查看</a></td>
        <td>{{$v['pay_money']}}元</td>
        <td>{{$expenditure_status[$v['status']]}}</td>
        <td>{{$pay_method[$v['pay_method']]}}</td>
        <td><textarea class="form-control" rows="1" cols="3" disabled>{{$v['remarks']}}</textarea></td>
        <td>
            <button type="button" class="btn btn-info btn-xs edit" data-id={{$v['id']}}>编辑</button>
        </td>
    </tr>
    @endforeach

</table>
{{$arr_expenditure->appends($arr_post_data)->withPath('expenditure_index')->onEachSide(1)->links()}}
@include('Admin.footer')