@include('Admin.header')
@include('Admin.customer_modal')
@include('Admin.customer_appoint_modal')
@include('Admin.customer_see_modal')
<form class="form-horizontal" action="/customer_index" method="get">
    <div class="form-group">
        <div class="col-xs-2">
            <input type="text" class="form-control" placeholder="请输客户姓名" name="select_customer_name" value="@if(isset($arr_post_data['select_customer_name'])){{$arr_post_data['select_customer_name']}}@endif" >
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control" placeholder="请输入公司名称" name="select_company_name" value="@if(isset($arr_post_data['select_company_name'])){{$arr_post_data['select_company_name']}}@endif" >
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control" placeholder="请输入电话或座机或微信" name="select_customer_phone" value="@if(isset($arr_post_data['select_customer_phone'])){{$arr_post_data['select_customer_phone']}}@endif" >
        </div>
        <div class="col-xs-2">
            <select class="form-control" name="select_customer_status">
                <option value=2>请选择客户状态</option>
                @foreach($customer_status as $k=>$v)
                    <option value="{{$k}}" @if(isset($arr_post_data['select_customer_status']) && $arr_post_data['select_customer_status']==$k) selected @endif>{{$v}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xs-2">
            <select class="form-control" name="select_customer_source">
                <option value=100>请选择客户来源</option>
                @foreach($customer_source as $k=>$v)
                    <option value="{{$k}}" @if(isset($arr_post_data['select_customer_source']) && $arr_post_data['select_customer_source']==$k) selected @endif>{{$v}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xs-2">
            <button type="submit" class="btn btn-primary" id='sub_select'>查询</button>
            <a href="/customer_index"><button type="button" class="btn btn-primary">重置</button></a>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-1" style="float: right">
            <button type="button" class="btn btn-primary" id="add_project">添加客户</button>
        </div>
    </div>
</form>
<div class="alert alert-info" role="alert">符合条件的一共有{{$customer_rows}}条数据</div>

<div class="progress" id="progress" data-num="1">
</div>

<table class="table table-hover">
    <tr>
        <th width="1%">ID</th>
        <th width="6%">客户名</th>
{{--        <th width="4%">类型</th>--}}
        <th width="10%">公司</th>
        <th width="14%">客户联系方式</th>
        <th width="7%">客户职位</th>
        <th width="15%">公司地址</th>
        <th width="6%">业务员</th>
{{--        <th width="15%">客户详情</th>--}}
        <th width="8%">是否老客户</th>
        <th width="4%">备注</th>
        <th width="4%">状态</th>
        <th width="10%">
            创建/修改时间
        </th>
        <th width="15%">操作</th>
    </tr>

    @foreach($arr_customer as $k=>$v)
    <tr>
        <td>{{$k+1}}</td>
        <td>{{$v['username']}}</td>
{{--        <td>{{$customer_type[$v['type']]}}</td>--}}
        <td>{{$v['company']}}</td>
        <td>
            手机:{{$v['phone']}}<br />
            座机:{{$v['landline']}}<br />
            微信:{{$v['wechat']}}
        </td>
        <td>{{$v['position']}}</td>
        <td>{{$v['address']}}</td>
        <td>{{$v['admin_name']}}</td>
{{--        <td>--}}
{{--            行业:{{$v['industry']}}<br />--}}
{{--            主营业务:{{$v['main_business']}}<br />--}}
{{--            公司规模:{{$v['scale']}}人<br />--}}
{{--            年营业额:{{$v['turnover']}}万元--}}
{{--        </td>--}}
        <td>{{$arr_new_customer[$v['is_new_customer']]}}</td>
        <td><textarea class="form-control" rows="1" cols="3" disabled>{{$v['remarks']}}</textarea></td>
        <td>{{$customer_status[$v['status']]}}</td>
        <td>
            @php echo date('Y-m-d',$v['create_time']); @endphp<br />
            @php echo date('Y-m-d',$v['last_time']); @endphp
        </td>
        <td>
            <button type="button" class="btn btn-info btn-xs see_customer" data-id={{$v['id']}}>查看</button>
            <button type="button" class="btn btn-info btn-xs edit" data-id={{$v['id']}}>编辑</button>
            <a class="btn btn-info btn-xs" href="/project_index?c_id={{$v['id']}}" role="button">查看项目</a>
            <br /><br />
            <button type="button" class="btn btn-info btn-xs appoint" data-cus_id={{$v['id']}} data-is_new={{$v['is_new_customer']}} data-admin_id={{$v['admin_id']}}>分配业务员</button>
        </td>
    </tr>
    @endforeach

</table>
{{$arr_customer->appends($arr_post_data)->withPath('customer_index')->onEachSide(1)->links()}}
@include('Admin.footer')