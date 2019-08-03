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
                <option value="">项目名</option>
                <?php foreach (json_decode($data)->project as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="layui-inline">
            <select name="customer_id" class="layui-input" lay-search>
                <option value="">客户名</option>
                <?php foreach (json_decode($data)->customer as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
            </select>
        </div>
        
        <div class="layui-inline">
            <select name="input_id" class="layui-input" lay-search>
                <option value="">记录人</option>
                <?php foreach (json_decode($data)->adminer as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
            </select>
        </div>
       
        <div class="layui-inline">
            <input class="layui-input" name="record_at" id="formdate" placeholder="记录日期" >
        </div>
        <button class="layui-btn" data-type="reload" lay-submit lay-filter="formDemo">搜索</button>
    </form>
</div>

<table id="demo" lay-filter="test"></table>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-xs" lay-event="detail">详情</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>


<ul class="layui-timeline" id='timelinebox' style='display: none; margin: 10px 20px;'></ul>

@include('Manage.layouts.project_record')


<script>
    layui.use(['table', 'form', 'laydate', 'jquery'], function () {
        var table = layui.table, $ = layui.jquery, form = layui.form, laydate = layui.laydate;
        var sysdata = <?php echo $data ?>;

        //第一个实例
        table.render({
            elem: '#demo'
            , id: 'recordtable'
            , url: '/getrecordlist' //数据接口
            , page: true //开启分页
            , cellMinWidth: 60
            , cols: [[//表头
                    {field: 'id', title: 'ID', width: 60, sort: true, fixed: 'left'}
                    , {field: 'project_name', title: '项目名', }
                    , {field: 'customer_name', title: '客户名', width: 100}
                    , {field: 'input_name', title: '记录人', width: 100}
                    , {field: 'record_at', title: '沟通日期', width: 120}
                    , {field: 'created_at', title: '添加时间'}
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
            table.reload('recordtable', {
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
                    getInfomsg(obj.data.id, '/getrecord');
                    $('.detailevent').css({display: 'none'});
                    $('.editevent').css({display: 'block'});
                    var index = layer.open({
                        type: 1,
                        title: "项目编辑",
                        area: ['60%', '80%'],
                        shadeClose: true,
                        shade: 0,
                        skin: 'layui-layer-rim',
                        content: $('#notebox'),
                        cancel: function (index, res) {

                        }
                    });
                    break;
                case 'detail':
                    getInfomsg(obj.data.id, '/getrecord');
                    $('.detailevent').css({display: 'block'});
                    $('.editevent').css({display: 'none'});
                    var index = layer.open({
                        type: 1,
                        title: "项目详情",
                        area: ['60%', '80%'],
                        shadeClose: true,
                        shade: 0,
                        skin: 'layui-layer-rim',
                        content: $('#notebox'),
                        cancel: function (index, res) {
                        }
                    });
                    break;
                case 'del':
                    layer.confirm('确定要删除该记录?', {icon: 3, title: '删除记录'}, function (index) {
                        $.ajax({
                            url: '/delrecord',
                            data: {_token: "{{ csrf_token() }}", _method: 'DELETE', record_id: obj.data.id},
                            type: 'post',
                            dataType: 'json',
                            success: function (res) {
                                if (res.code == 200) {
                                    table.reload('recordtable', {});
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
                data: {record_id: cuid},
                type: "get",
                dataType: 'json',
                success: function (res) {
                    if (res.code == 200) {
                        if (url == '/getrecordlist') {
                            console.log(res);
                        } else {
                            forvalrecord(res.data);
                        }
                    } else {
                        layer.msg(res.msg, {
                            icon: 5
                        });
                        return false;
                    }
                }
            });
        }

        var forvalrecord = function (data) {
            form.val("noteform", {
                editid: data.id
                ,project_id: data.project_id
                ,project_name: data.project_name
                ,customer_id: data.customer_id
                ,customer_name: data.customer_name
                ,result: data.result
                ,process: data.process
                ,question: data.question
                ,record_at: data.record_at
            });
        }


        form.on('submit(recordsubform)', function (data) {
            $('#recordsubform').addClass('layui-btn-disabled');
            $('#recordsubform').attr('disabled', 'disabled');
            $.ajax({
                url: '/addrecord',
                data: data.field,
                type: "post",
                dataType: 'json',
                success: function (res) {
                    if (res.code == 200) {
//                        layer.closeAll();
                        layer.msg("成功", {
                            icon: 1
                        });
                        forvalrecord({});
                        table.reload('recordtable', {});
                    } else {
                        layer.msg(res.msg, {
                            icon: 5
                        });
                        return false;
                    }
                   $('#recordsubform').removeClass('layui-btn-disabled');
                   $('#recordsubform').attr('disabled', ''); 
                }
            });
            return false;
        });
    });

</script>



@endsection
