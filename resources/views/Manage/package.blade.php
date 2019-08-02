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
                <option value="">选择项目</option>
                <?php foreach (json_decode($data)->project as $key => $val) { ?>
                    <option value="<?php echo $key ?>"><?php echo $val ?></option>
                <?php } ?>
            </select>
        </div>
        
        <div class="layui-inline">
            <select name="input_id" class="layui-input"  lay-search>
                <option value="">录入人</option>
                <?php foreach (json_decode($data)->adminer as $key => $val) { ?>
                    <option value="<?php echo $key ?>"><?php echo $val ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="layui-inline">
            <input class="layui-input" name="created_at" id="formdate" placeholder="添加时间" >
        </div>
        <button class="layui-btn" data-type="reload" lay-submit lay-filter="formDemo">搜索</button>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <button class="layui-btn" data-type="reload" id="addContract">添加项目程序包</button>
    </form>
</div>

<table id="demo" lay-filter="test"></table>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-xs" lay-event="detail">详情</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>


<ul class="layui-timeline" id='timelinebox' style='display: none; margin: 10px 20px;'></ul>

@include('Manage.layouts.package_form')


<script>
    layui.use(['table', 'form', 'laydate', 'jquery', 'upload'], function () {
        var table = layui.table, $ = layui.jquery, form = layui.form, laydate = layui.laydate, upload = layui.upload;
        var sysdata = <?php echo $data ?>;

        //第一个实例
        table.render({
            elem: '#demo'
            , id: 'packagestable'
            , url: '/packagelist' //数据接口
            , page: true //开启分页
            , cellMinWidth: 60
            , cols: [[//表头
                    {field: 'id', title: 'ID', width: 60, sort: true, fixed: 'left'}
                    , {field: 'project_name', title: '项目名', width: 200}
                    , {field: 'input_name', title: '录入人', width: 100}
                    , {field: 'package_app', title: 'app包', width: 100, templet: function(d){
                            return d.package_app ? '<a class="layui-btn" href="'+ d.package_app +'" target="_blank" style="line-height: 28px;"> 下载 </a>' : '';
                    }}
                    , {field: 'app_size', title: 'app大小', width: 100}
                    , {field: 'package_web', title: 'web包', width: 120, templet: function(d){
                            return d.package_web ? '<a class="layui-btn" href="'+ d.package_web +'" target="_blank" style="line-height: 28px;"> 下载 </a>' : '';
                    }}
                    , {field: 'web_size', title: 'web包大小', width: 100}
                    , {field: 'package_sql', title: 'SQL包', width: 120, templet: function(d){
                            return d.package_sql ? '<a class="layui-btn" href="'+ d.package_sql +'" target="_blank" style="line-height: 28px;"> 下载 </a>' : '';
                    }}
                    , {field: 'sql_size', title: 'SQL包大小', width: 100}
                    , {field: 'created_at', title: '添加时间', width: 170}
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
            table.reload('packagestable', {
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
                    getInfomsg(obj.data.id, '/getpackage');
                    $('.detailevent').css({display: 'none'});
                    $('.editevent').css({display: 'block'});
                    var index = layer.open({
                        type: 1,
                        title: "项目程序包编辑",
                        area: ['60%', '80%'],
                        shadeClose: true,
                        shade: 0,
                        skin: 'layui-layer-rim',
                        content: $('#contractbox'),
                        cancel: function (index, res) {

                        }
                    });
                    break;
                case 'detail':
                    getInfomsg(obj.data.id, '/getpackage');
                    $('.detailevent').css({display: 'block'});
                    $('.editevent').css({display: 'none'});
                    var index = layer.open({
                        type: 1,
                        title: "项目程序包详情",
                        area: ['60%', '80%'],
                        shadeClose: true,
                        shade: 0,
                        skin: 'layui-layer-rim',
                        content: $('#contractbox'),
                        cancel: function (index, res) {
                        }
                    });
                    break;
                case 'del':
                    layer.confirm('确定要删除该项目程序包?', {icon: 3, title: '删除项目程序包'}, function (index) {
                        $.ajax({
                            url: '/delpackage',
                            data: {_token: "{{ csrf_token() }}", _method: 'DELETE', packages_id: obj.data.id},
                            type: 'post',
                            dataType: 'json',
                            success: function (res) {
                                if (res.code == 200) {
                                    table.reload('packagestable', {});
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
                data: {packages_id: cuid},
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
            form.val("contractform", {
                editid: data.id
                , project_id: data.project_id
                , remarks: data.remarks
            });
        }


        $('#addContract').click(function () {
            forvalrecord({});
            $('.detailevent').css({display: 'none'});
            $('.editevent').css({display: 'block'});
            var index = layer.open({
                type: 1,
                title: "添加项目程序包",
                area: ['60%', '80%'],
                shadeClose: true,
                shade: 0,
                skin: 'layui-layer-rim',
                content: $('#contractbox'),
                cancel: function (index, res) {

                }
            });
        });

        var uppackagesfiles = upload.render({
            elem: '.uppackages'
            , url: '/uppackagefiles'
            , accept: 'file'
            , field: 'packagesfiles'
            , data: {_token: "{{ csrf_token() }}"}
            , choose: function(res){
                console.log(res);
            }
            , before: function (obj) {
                layer.load();
            }
            , done: function (res, index, upload) {
                var item = this.item;
                if (res.code == 200) {
                    $('input[name=package_'+item[0].id+']').val(res.data.path);
                    $('input[name=size_'+item[0].id+']').val(res.data.size);
                } else {
                    layer.msg(res.msg, {
                        icon: 5
                    });
                }
                layer.closeAll('loading'); //关闭loading
            }
            , error: function () {
                layer.closeAll('loading'); //关闭loading
                //请求异常回调
            }
        });


        $('#subform').click(function () {
            form.on('submit(contractform)', function (data) {
                console.log(data);
                $.ajax({
                    url: '/addpackage',
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
                            table.reload('packagestable', {});
                        } else {
                            layer.msg(res.msg, {
                                icon: 5
                            });
                            return false;
                        }
                    }
                });
                return false;
            });
        });
    });

</script>



@endsection
