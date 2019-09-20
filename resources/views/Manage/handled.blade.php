@extends('Manage.layouts.app')

@section('content')
@parent

<fieldset class="layui-elem-field layui-field-title" style="margin-top: 10px;">
    <legend>{{$title}}</legend>
</fieldset>

<div class="demoTable">
    <form class="layui-form layui-form-item" action="" onsubmit="return false;">
        <div class="layui-inline">
            <select name="process_id" class="layui-input"  lay-search>
                <option value="">已下单项目</option>
                <?php foreach (json_decode($data)->process as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="layui-inline">
            <select name="status" class="layui-input"  lay-search>
                <option value="">开发进度</option>
                <?php foreach (json_decode($data)->status as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="layui-inline">
            <input class="layui-input" name="develop_date" id="formdate" placeholder="下单日期" >
        </div>
        <button class="layui-btn" data-type="reload" lay-submit lay-filter="formDemo">搜索</button>
    </form>
</div>

<table id="demo" lay-filter="test"></table>

<ul class="layui-timeline" id='timelinebox' style='display: none; margin: 10px 20px;'></ul>

@include('Manage.layouts.process_addnote')
@include('Manage.layouts.process_add')


<script>
    layui.use(['table', 'form', 'laydate', 'jquery', 'upload'], function () {
        var table = layui.table, $ = layui.jquery, form = layui.form, laydate = layui.laydate, upload = layui.upload;
        var sysdata = <?php echo $data ?>;
        
        //第一个实例
        table.render({
            elem: '#demo'
            , id: 'tablelist'
            , url: '/process/handledlist' //数据接口
            , page: true //开启分页
            , cellMinWidth: 60
            , cols: [[//表头
                    {field: 'id', title: 'ID', width: 60, sort: true, fixed: 'left'}
                    , {field: 'project_name', title: '签约项目', width: 200}
                    , {title: '开发进度', width: 160, templet: function(d){
                            return sysdata.status[d.status] ;
                    }}
                    , {field: 'over_date', title: '指定完成日期', width: 200}
                    , {field: 'end_date', title: '实际完成日期', width: 300}
                    , {field: 'technical_str', title: '技术负责人', width: 120}
                    , {field: 'admin_str', title: '监督负责人', width: 120}
                    , {field: 'created_at', title: '记录日期', width: 300}
                    , {fixed: 'right', title: '操作', width: 160, templet: function(d){
                            var toolstr = (d.status == d.step) ? '<a class="layui-btn layui-btn-xs" lay-event="edit">修改记录</a>' : '';
                            toolstr += '<a class="layui-btn layui-btn-xs" lay-event="detail">记录详情</a>';
                            return toolstr;
                    } } 
                ]]
            , response: {
                statusCode: 200 //规定成功的状态码，默认：0
                , countName: 'total' //规定数据总数的字段名称，默认：count
            }
        });

        laydate.render({
            elem: '#formdate' //指定元素
            , range: '@'
        });
        lay('.formdate').each(function () {
            laydate.render({
                elem: this
                , trigger: 'click'
            });
        });

        //监听提交
        form.on('submit(formDemo)', function (data) {
//            layer.msg(JSON.stringify(data.field));
            table.reload('tablelist', {
                where: data.field,
                page: {
                    curr: 1,
                }
            });
        });

        table.on('tool(test)', function (obj) { console.log(obj.data);
            var data = obj.data;  
            switch (obj.event) {
                case 'edit':
                    noteval({
                        editid: data.id
                        , process_id: data.process_id
                        , process_name: data.project_name
                        , over_date: data.over_date
                        , end_date: data.end_date
                        , status: data.status
                        , develop_id: data.develop_id
                        , note: data.note
                        , remarks: data.remarks
                    });console.log(data.id);
                    $('.detailevent').css({display: 'none'});
                    $('.editevent').css({display: 'block'});
                    var index = layer.open({
                        type: 1,
                        title: "提交开发记录",
                        area: ['60%', '80%'],
                        shadeClose: true,
                        shade: 0,
                        skin: 'layui-layer-rim',
                        content: $('#addnote'),
                        cancel: function (index, res) {
//                            $('#subform').removeClass('layui-btn-disabled');
//                            $('#subform').removeAttr('disabled'); 
                        }
                    });
                    break;
                case 'detail':
                    noteval({
                         process_id: data.id
                        , process_name: data.project_name
                        , over_date: data.over_date
                        , end_date: data.end_date
                        , status: data.status
                        , develop_id: data.develop_id
                        , note: data.note
                        , remarks: data.remarks
                    });
                    $('.detailevent').css({display: 'block'});
                    $('.editevent').css({display: 'none'});
                    var index = layer.open({
                        type: 1,
                        title: "项目下单表详情",
                        area: ['60%', '80%'],
                        shadeClose: true,
                        shade: 0,
                        skin: 'layui-layer-rim',
                        content: $('#addnote'),
                        cancel: function (index, res) {
                        }
                    });
                    break;
            }
        });


        var noteval = function (data) {
            form.val("addnoteform", {
                 process_id: data.process_id
                , process_name: data.process_name
                , over_date: data.over_date
                , end_date: data.end_date
                , status: data.status
                , develop_id: data.develop_id
                , remarks: data.remarks
                , note: data.note
            });
        }


        var formval = function (data) {
            form.val("processform", {
                 editid: data.id
                , project_id: data.project_id
                , salesman_id: data.admin_id
                , develop_date: data.develop_date
                , deliver_date: data.deliver_date
                , technical_id: data.technical_id
                , admin_id: data.admin_id
                , develop_id: data.develop_id
                , customer_str: data.customer_str
                , company_str: data.company_str
                , note: data.note
            });
        }


            form.on('submit(subform)', function (data) {  
                $('#subform').addClass('layui-btn-disabled');
                $('#subform').attr('disabled', 'disabled');
                $.ajax({
                    url: '/process/addnote',
                    data: data.field,
                    type: "post",
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 200) {
                            layer.closeAll();
                            layer.msg("成功", {
                                icon: 1
                            });
                            layer.closeAll();
                            formval({});
                            table.reload('tablelist', {});
                        } else {
                            layer.msg(res.msg, {
                                icon: 5
                            });
                        } 
                    },
                    complete: function(res){ 
                        $('#subform').removeClass('layui-btn-disabled');
                        $('#subform').removeAttr('disabled'); 
                    }
                });
                return false;
            });
    });

</script>



@endsection
