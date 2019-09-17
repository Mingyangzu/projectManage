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
            <select name="customer_id" class="layui-input"  lay-search>
                <option value="">签约客户</option>
                <?php foreach (json_decode($data)->customer as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="layui-inline">
            <input class="layui-input" name="title" id="demoReload" placeholder="合同名" >
        </div>

        <div class="layui-inline">
            <input class="layui-input" name="take_effect_time" id="formdate" placeholder="签约时间" >
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
            , url: '/contractlist' //数据接口
            , page: true //开启分页
            , cellMinWidth: 60
            , cols: [[//表头
                    {field: 'id', title: 'ID', width: 60, sort: true, fixed: 'left'}
                    , {field: 'title', title: '合同名', width: 200}
                    , {field: 'name', title: '项目名', width: 200}
                    , {field: 'customer_name', title: '客户名', width: 100}
                    , {field: 'money', title: '合同金额', width: 100}
                    , {field: 'take_effect_time', title: '签约日期', width: 120}
                    , {field: 'contract_time', title: '生效日期', width: 120}
                    , {field: 'end_time', title: '截止日期', width: 120}
                    , {title: '合同文件', width: 120, templet: function(d){
                            return d.url ? '<a class="layui-btn layui-btn-xs" href="'+ d.url +'" target="_blank"> &nbsp;&nbsp; 下载 &nbsp;&nbsp;</a>' : '';
                    }}
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
                    getInfomsg(obj.data.id, '/getcontract');
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

                        }
                    });
                    break;
                case 'detail':
                    getInfomsg(obj.data.id, '/getcontract');
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
                            url: '/delcontract',
                            data: {_token: "{{ csrf_token() }}", _method: 'DELETE',contract_id: obj.data.id},
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
                data: {contract_id: cuid},
                type: "get",
                dataType: 'json',
                success: function (res) {
                    if (res.code == 200) {
                        forvalrecord(res.data);
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
            form.val("processform", {
                editid: data.id
                , title: data.title
                , project_id: data.project_id
                , money: data.money
                , describe: data.describe
                , take_effect_time: data.take_effect_time
                , contract_time: data.contract_time
                , end_time: data.end_time
                , url: data.url
            });
        }


        $('#addProcess').click(function () {
            forvalrecord({});
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
//                $('#subform').addClass('layui-btn-disabled');
//                $('#subform').attr('disabled', 'disabled');
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
                            forvalrecord({});
                            table.reload('tablelist', {});
                        } else {
                            layer.msg(res.msg, {
                                icon: 5
                            });
                            return false;
                        }
//                        $('#subform').removeClass('layui-btn-disabled');
//                        $('#subform').removeAttr('disabled'); 
                    }
                });
                return false;
            });
    });

</script>



@endsection
