@include('Admin.header')
@include('Admin.wages_modal')
@include('Admin.wages_see_modal')
<form class="form-horizontal" action="/wages_index" method="get">
    <div class="form-group">
        <div class="col-xs-2">
            <select class="form-control" name="select_user_name">
                <option value="0">请选择员工</option>
                @foreach($admin_list as $v)
                    <option value="{{$v->id}}" @if(isset($arr_post_data['select_user_name']) && $arr_post_data['select_user_name']==$v->id) selected @endif>{{$v->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xs-2">
            <select class="form-control" name="select_wages_status">
                <option value=2>请选择薪资状态</option>
                @foreach($wages_status as $k=>$v)
                    <option value="{{$k}}" @if(isset($arr_post_data['select_wages_status']) && $arr_post_data['select_wages_status']==$k) selected @endif>{{$v}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control datetimepicker" placeholder="请选择薪资时间" name="select_wages_time" readonly value="@if(isset($arr_post_data['select_wages_time'])){{$arr_post_data['select_wages_time']}}@endif" >
        </div>
        <div class="col-xs-2">
            <button type="submit" class="btn btn-primary" id='sub_select'>查询</button>
            <a href="/wages_index"><button type="button" class="btn btn-primary">重置</button></a>
        </div>
        <div class="col-xs-1" style="float: right">
            <button type="button" class="btn btn-primary" id="add_project">添加薪资</button>
        </div>
    </div>
</form>
<div class="alert alert-info" role="alert">符合条件的一共有{{$wages_rows}}条数据</div>

<div class="progress" id="progress" data-num="1">
</div>

<table class="table table-hover">
    <tr>
        <th>ID</th>
        <th>员工姓名</th>
        <th>操作者</th>
        <th>薪资时间</th>
        <th>总薪资</th>
        <th>薪资收入</th>
        <th>薪资扣除</th>
        <th>实发薪资</th>
        <th>备注</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>最后修改时间</th>
        <th>操作</th>
    </tr>

    @foreach($arr_wages as $k=>$v)
    <tr>
        <td>{{$k+1}}</td>
        <td>{{$v['admin_name']}}</td>
        <td>{{$v['operator_name']}}</td>
        <td>@php echo date('Y-m',$v['time']); @endphp</td>
        <td>{{$v['total']}}</td>
        <td>
{{--            基本薪资:{{$v['basic']}}<br />--}}
{{--            岗位薪资:{{$v['post']}}<br />--}}
{{--            全勤:{{$v['full']}}<br />--}}
{{--            学历:{{$v['education']}}<br />--}}
{{--            年限薪资:{{$v['years']}}<br />--}}
{{--            经营津贴:{{$v['oper_allowance']}}<br />--}}
{{--            技能薪资:{{$v['skill']}}<br />--}}
{{--            应发绩效:{{$v['payable_merit']}}<br />--}}
{{--            提成:{{$v['royalty']}}<br />--}}
{{--            特殊津贴:{{$v['perquisites']}}<br />--}}
{{--            奖金:{{$v['bonus']}}<br />--}}
            应发薪资:{{$v['payroll']}}
        </td>
        <td>
{{--            社保扣除:{{$v['social_security']}}<br />--}}
{{--            绩效扣除:{{$v['deduct_merit']}}<br />--}}
{{--            请假:{{$v['leave']}}<br />--}}
{{--            迟到:{{$v['late']}}<br />--}}
            扣除薪资:{{$v['deduct_salary']}}<br />
        </td>
        <td>{{$v['actual_salary']}}</td>
        <td><textarea class="form-control" rows="1" cols="3" disabled>{{$v['remarks']}}</textarea></td>
        <td>{{$wages_status[$v['status']]}}</td>
        <td>@php echo date('Y-m-d',$v['create_time']); @endphp</td>
        <td>@php echo date('Y-m-d',$v['last_time']); @endphp</td>
        <td>
            <button type="button" class="btn btn-info btn-xs see_wages" data-id={{$v['id']}}>查看详情</button>
            <button type="button" class="btn btn-info btn-xs edit" data-id={{$v['id']}}>编辑</button>
        </td>
    </tr>
    @endforeach

</table>
{{$arr_wages->appends($arr_post_data)->withPath('wages_index')->onEachSide(1)->links()}}
@include('Admin.footer')