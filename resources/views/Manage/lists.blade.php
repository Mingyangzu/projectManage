@extends('Manage.layouts.app')

@section('content')
@parent

<fieldset class="layui-elem-field layui-field-title" style="margin-top: 10px;">
    <legend>{{$title}}</legend>
</fieldset>

<div class="demoTable">
    <form class="layui-form layui-form-item" action="" onsubmit="return false;">
        <div class="layui-inline">
            <select name="project_id" class="layui-input"  lay-search>
                <option value="">签约项目</option>
                <?php foreach (json_decode($data)->contract_project as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
            </select>
        </div>
        
        <div class="layui-inline">
            <select name="salesman_id" class="layui-input"  lay-search>
                <option value="">签单人</option>
                <?php foreach (json_decode($data)->adminer as $k => $v) { ?>
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
        &nbsp;&nbsp;&nbsp;&nbsp;
        <button class="layui-btn" data-type="reload" id="addProcess">添加项目下单表</button>
    </form>
</div>

<table id="demo" lay-filter="test"></table>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-xs" lay-event="detail">详情</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>


<ul class="layui-timeline" id='timelinebox' style='display: none; margin: 10px 20px;'></ul>

@include('Manage.layouts.process_add')


<script>
    layui.use(['table', 'form', 'laydate', 'jquery', 'upload'], function () {
        var table = layui.table, $ = layui.jquery, form = layui.form, laydate = layui.laydate, upload = layui.upload;
        var sysdata = <?php echo $data ?>;
        
        //第一个实例
        table.render({
            elem: '#demo'
            , id: 'tablelist'
            , url: '/process/getlists' //数据接口
            , page: true //开启分页
            , cellMinWidth: 60
            , cols: [[//表头
                    {field: 'id', title: 'ID', width: 60, sort: true, fixed: 'left'}
                    , {field: 'project_name', title: '签约项目', width: 200}
                    , {field: 'customer_str', title: '客户信息', width: 200}
                    , {field: 'company_str', title: '公司信息', width: 300}
                    , {field: 'salesman_str', title: '签单人', width: 120}
                    , {title: '开发进度', width: 160, templet: function(d){
                            return sysdata.status[d.status] ;
                    }}
                    , {field: 'name', title: '当前执行人', width: 120}
                    , {field: 'technical_str', title: '技术负责人', width: 120}
                    , {field: 'admin_str', title: '监督负责人', width: 120}
                    , {field: 'develop_date', title: '下单日期', width: 120}
                    , {field: 'deliver_date', title: '完结日期', width: 120}
                    , {fixed: 'right', title: '操作', toolbar: '#barDemo', width: 160}
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

        table.on('tool(test)', function (obj) {
            var data = obj.data;
            switch (obj.event) {
                case 'edit':
                    getInfomsg(obj.data.id, '/process/edit');
                    $('#subform').text('编辑');
                    $('.detailevent').css({display: 'none'});
                    $('.editevent').css({display: 'block'});
                    var index = layer.open({
                        type: 1,
                        title: "项目下单表编辑",
                        area: ['60%', '80%'],
                        shadeClose: true,
                        shade: 0,
                        skin: 'layui-layer-rim',
                        content: $('#editbox'),
                        cancel: function (index, res) {
//                            $('#subform').removeClass('layui-btn-disabled');
//                            $('#subform').removeAttr('disabled'); 
                        }
                    });
                    break;
                case 'detail':
                    getInfomsg(obj.data.id, '/process/detail');
                    $('.detailevent').css({display: 'block'});
                    $('.editevent').css({display: 'none'});
                    var index = layer.open({
                        type: 1,
                        title: "项目下单表详情",
                        area: ['60%', '80%'],
                        shadeClose: true,
                        shade: 0,
                        skin: 'layui-layer-rim',
                        content: $('#editbox'),
                        cancel: function (index, res) {
                        }
                    });
                    break;
                case 'del':
                    layer.confirm('确定要删除该项目下单表?', {icon: 3, title: '删除项目下单表'}, function (index) {
                        $.ajax({
                            url: '/process/del',
                            data: {_token: "{{ csrf_token() }}", _method: 'DELETE',del_id: obj.data.id},
                            type: 'post',
                            dataType: 'json',
                            success: function (res) {
                                if (res.code == 200) {
                                    table.reload('tablelist', {});
                                } else {
                                    layer.msg(res.msg, {icon: 5});
                                }
                            }
                        });
                        layer.close(index);
                    });
                    break;
            }
        });

        var getInfomsg = function (cuid, url) {
            if (!cuid || !url) {
                layer.msg('提交有误!', {
                    icon: 5
                });
                return false;
            }
            $.ajax({
                url: url,
                data: {eid: cuid},
                type: "get",
                dataType: 'json',
                success: function (res) {
                    if (res.code == 200) {
                        formval(res.data);
                    } else {
                        layer.msg(res.msg, {
                            icon: 5
                        });
                        return false;
                    }
                }
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
                , status: data.status
                , develop_id: data.develop_id
                , customer_str: data.customer_str
                , company_str: data.company_str
                , note: data.note
            });
        }


        $('#addProcess').click(function () {
            formval({});
            $('#subform').text('添加');
            $('.detailevent').css({display: 'none'});
            $('.editevent').css({display: 'block'});
            var index = layer.open({
                type: 1,
                title: "项目下单表添加",
                area: ['60%', '80%'],
                shadeClose: true,
                shade: 0,
                skin: 'layui-layer-rim',
                content: $('#editbox'),
                cancel: function (index, res) {

                }
            });
        });


            form.on('submit(subform)', function (data) {  
                $('#subform').addClass('layui-btn-disabled');
                $('#subform').attr('disabled', 'disabled');
                data.field.admin_str = sysdata.adminer[data.field.admin_id];
                data.field.salesman_str = sysdata.adminer[data.field.salesman_id];
                data.field.technical_str = sysdata.adminer[data.field.technical_id];
                $.ajax({
                    url: '/process/addprocess',
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
