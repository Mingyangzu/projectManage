@extends('Manage.layouts.app')


@section('content')
@parent

<style>
    .tjbody{display: flex; width: 100%; height: 100%; justify-content: space-around;}
    .tjbox{width: 180px; height: 200px; padding: 20px 10px; color: #fff; font-size: 16px;}
    .bluebox{border: 1px solid #fff; border-radius: 10px; background-color: #36A9CE; box-shadow: 5px 5px 3px #888;}
    .greenbox{border: 1px solid #fff; border-radius: 10px; background-color: #4B7902; box-shadow: 5px 5px 3px #888;}
    .yellowbox{border: 1px solid #fff; border-radius: 10px; background-color: #B8741A; box-shadow: 5px 5px 3px #888;}
    .tjbox .titles{widht: 100%;font-size: 18px; font-weight: 600;text-align: center;margin-bottom: 20px;letter-spacing:4px;}
    .tjbox .tjnums{line-height: 30px;text-indent: 2em;letter-spacing:2px; font-style:italic;}
</style>

<fieldset class="layui-elem-field layui-field-title" style="margin-top: 10px;">
    <legend>{{$title}}</legend>
</fieldset>

<div class="tjbody">
    <div class="tjbox bluebox">
        <p class='titles'> 客户数 </p>
        <p class='tjnums'> 本月新增: {{$total['customer_month']}} </p>
        <p class='tjnums'> 总计: {{$total['customer_total']}} </p>
    </div>
    <div class="tjbox greenbox">
        <p class='titles'> 项目数 </p>
        <p class='tjnums'> 跟踪中: {{$total['contract_start']}} </p>
        <p class='tjnums'> 开发中: {{$total['contract_working']}} </p>
        <p class='tjnums'> 已完结: {{$total['contract_end']}} </p>
        <p class='tjnums'> 总计: {{$total['customer_total']}} </p>
    </div>
    <div class="tjbox yellowbox">
        <p class='titles'> 签约数 </p>
        <p class='tjnums'> 本月签约: {{$total['signing_month']}} </p>
        <p class='tjnums'> 总签约: {{$total['signing_total']}} </p>
    </div>
</div>



@endsection