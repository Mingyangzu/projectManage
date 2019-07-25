@include('Admin.header')
@include('Admin.contract_modal')
<form class="form-horizontal" action="/contract_index" method="get">
    <div class="form-group">
        <div class="col-xs-2">
            <input type="text" class="form-control" placeholder="请输入项目名称" name="select_project_name" value="@if(isset($arr_post_data['select_project_name'])){{$arr_post_data['select_project_name']}}@endif" >
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control" placeholder="请输入合同标题" name="select_contract_title" value="@if(isset($arr_post_data['select_contract_title'])){{$arr_post_data['select_contract_title']}}@endif" >
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control" placeholder="请输入合同金额" name="select_contract_money" value="@if(isset($arr_post_data['select_contract_money'])){{$arr_post_data['select_contract_money']}}@endif" >
        </div>
        <div class="col-xs-2">
            <select class="form-control" name="select_contract_status">
                <option value=2>请选择合同状态</option>
                <option value="1" @if(isset($arr_post_data['select_contract_status']) && $arr_post_data['select_contract_status']==1) selected @endif>生效</option>
                <option value="0" @if(isset($arr_post_data['select_contract_status']) && $arr_post_data['select_contract_status']==0) selected @endif>无效</option>
            </select>
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control datetimepicker" placeholder="请选择签约时间" name="select_contract_time" readonly value="@if(isset($arr_post_data['select_contract_time'])){{$arr_post_data['select_contract_time']}}@endif" >
        </div>
        <div class="col-xs-2">
            <button type="submit" class="btn btn-primary" id='sub_select'>查询</button>
            <a href="/contract_index"><button type="button" class="btn btn-primary">重置</button></a>
        </div>
        <div class="col-xs-1" style="float: right">
            <button type="button" class="btn btn-primary" id="add_project">添加合同</button>
        </div>
    </div>
</form>
<div class="alert alert-info" role="alert">符合条件的一共有{{$contract_rows}}条数据</div>

<div class="progress" id="progress" data-num="1">
</div>

<table class="table table-hover">
    <tr>
        <th>ID</th>
        <th width="10%">公司名</th>
        <th width="10%">项目名</th>
        <th width="10%">合同标题</th>
        <th>合同金额</th>
        <th>查看</th>
        <th>合同描述</th>
        <th>合同状态</th>
        <th>签约时间</th>
        <th>生效时间</th>
        <th>截止时间</th>
        <th>
            创建时间<br />
            最后修改时间
        </th>
        <th>操作</th>
    </tr>

    @foreach($arr_contract as $k=>$v)
    <tr>
        <td>{{$k+1}}</td>
        <td>{{$v['company']}}</td>
        <td>{{$v['project_name']}}</td>
        <td>{{$v['title']}}</td>
        <td>{{$v['money']}}元</td>
        <td><a target="_blank" href="{{$app_url}}/{{$v['url']}}">下载</a></td>
        <td><textarea class="form-control" rows="1" cols="3" disabled>{{$v['describe']}}</textarea></td>
        <td>{{$arr_contract_status[$v['status']]}}</td>
        <td>@php echo date('Y-m-d',$v['contract_time']); @endphp</td>
        <td>@php echo date('Y-m-d',$v['take_effect_time']); @endphp</td>
        <td>@php echo date('Y-m-d',$v['end_time']); @endphp</td>
        <td>
            @php echo date('Y-m-d',$v['create_time']); @endphp<br />
            @php echo date('Y-m-d',$v['last_time']); @endphp
        </td>
        <td>
            <button type="button" class="btn btn-info btn-xs edit" data-id={{$v['id']}}>编辑</button>
            <a class="btn btn-info btn-xs" href="/finance_index?c_id={{$v['id']}}" role="button">查看收款</a>
        </td>
    </tr>
    @endforeach

</table>
{{$arr_contract->appends($arr_post_data)->withPath('contract_index')->onEachSide(1)->links()}}
@include('Admin.footer')