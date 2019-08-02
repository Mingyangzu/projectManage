@extends('Manage.layouts.app')

@section('content')
@parent

<fieldset class="layui-elem-field layui-field-title" style="margin-top: 10px;">
    <legend>{{$title}}</legend>
</fieldset>

<div class="demoTable">
    <form class="layui-form layui-form-item" action="" onsubmit="return false;">
        <div class="layui-inline">
            <input class="layui-input" name="name" id="demoReload" placeholder="项目名" >
        </div>
        
        <div class="layui-inline">
            <select name="customer_id" class="layui-input" lay-search>
                <option value="">客户名 输入文字筛选</option>
                <?php foreach (json_decode($data)->customer as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
            </select>
        </div>
        
        <div class="layui-inline">
            <select name="admin_id" class="layui-select" lay-search>
                <option value="">业务员 输入文字筛选</option>
                <?php foreach (json_decode($data)->adminer as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
            </select>
        </div>
        
        
        <div class="layui-inline">
            <input class="layui-input" name="deliver_date" id="formdate" placeholder="交付日期" >
        </div>
        
        <div class="layui-input-inline">
            <select name="status" class="layui-select" >
                <option value="">项目进度</option>
                <?php foreach (json_decode($data)->status as $key => $val) { ?>
                    <option value="<?php echo $key ?>"><?php echo $val ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="layui-input-inline">
            <select name="payment_status" class="layui-select" >
                <option value="">财务状态</option>
                <?php foreach (json_decode($data)->pay_status as $key => $val) { ?>
                    <option value="<?php echo $key ?>"><?php echo $val ?></option>
                <?php } ?>
            </select>
        </div>
        
        <div class="layui-inline" style="width: 400px;">
            <select name="type_id" class="layui-select" xm-select="select_type_id" >
                <option value="">项目类型</option>
                <?php foreach (json_decode($data)->type as $k => $v) { ?>
                    <option value="<?php echo $v ?>"><?php echo $v ?></option>
                <?php } ?>
            </select>
        </div>
        
        <button class="layui-btn" data-type="reload" lay-submit lay-filter="formDemo">搜索</button>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <button class="layui-btn" data-type="reload" id="addProject">添加项目</button>
    </form>
</div>

<table id="demo" lay-filter="test"></table>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="addrecord">添加记录</a>
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-xs" lay-event="detail">详情</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>

<script type="text/html" id="types"> 
    @{{# if(d.type == 1){  }}
    企业
    @{{# }else{ }}
    个人
    @{{# } }}
</script>

@include('Manage.layouts.project_form')

<ul class="layui-timeline" id='timelinebox' style='display: none; margin: 10px 20px;'></ul>


@include('Manage.layouts.project_record')


<script>
    layui.use(['table', 'form', 'laydate', 'jquery'], function () {
        var table = layui.table, $ = layui.jquery, form = layui.form, laydate = layui.laydate;
        var formSelects = layui.formSelects;
        var sysdata = <?php echo $data ?>;
        
        formSelects.btns('select_type_id', ['select', 'remove'], {show: 'icon'});
        formSelects.btns('input_type_id', ['select', 'remove'], {show: 'icon'});

        //第一个实例
        table.render({
            elem: '#demo'
            , id: 'projecttable'
            , url: '/projectlist' //数据接口
            , page: true //开启分页
            , cellMinWidth: 60
            , cols: [[//表头
                    {field: 'id', title: 'ID', width: 60, sort: true, fixed: 'left'}
                    , {field: 'name', title: '项目名', width: 150}
                    , {field: 'customer_name', title: '客户名', width: 100}
                    , {field: 'create_time', title: '录入日期', width: 120}
                    , {field: 'last_time', title: '更新日期', width: 120}
                    , {field: 'admin_name', title: '业务员', width: 100}
                    , {field: 'total', title: '沟通记录数', width: 100, templet: function (d) {
                            return d.total > 0 ? '<a class="layui-btn layui-btn-xs" lay-event="recordlist"> &nbsp;&nbsp;' + d.total + ' &nbsp;&nbsp;</a>' : 0 ;
                        }}
                    , {field: 'type_id', title: '类型', templet: '#types', width: 200, templet: function (d) {
                            var str = '';
                            for(var i in d.type_id){
                                str += d.type_id[i] + ' ';
                            }
                            return str ;
                        }}
                    , {field: 'status', title: '项目状态', width: 100, templet: function (d) {
                            return sysdata['status'][d.status];
                        }}
                    , {field: 'payment_status', title: '财务状态', width: 100, templet: function (d) {
                            return sysdata['pay_status'][d.payment_status];
                        }}
                    , {field: 'develop_date', title: '开发日期', width: 120}
                    , {field: 'deliver_date', title: '交付日期', width: 120}
                    , {field: 'surplus', title: '剩余天数', width: 100}
                    , {fixed: 'right', title: '操作', toolbar: '#barDemo', width: 230}
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
            table.reload('projecttable', {
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
                    getInfomsg(obj.data.id, '/getproject');
                    $('.detailevent').css({display: 'none'});
                    $('.editevent').css({display: 'block'});
                    var index = layer.open({
                        type: 1,
                        title: "项目编辑",
                        area: ['60%', '80%'],
                        shadeClose: true,
                        shade: 0,
                        skin: 'layui-layer-rim',
                        content: $('#contentbox'),
                        cancel: function (index, res) {

                        }
                    });
                    break;
                case 'detail':
                    getInfomsg(obj.data.id, '/getproject');
                    $('.detailevent').css({display: 'block'});
                    $('.editevent').css({display: 'none'});
                    var index = layer.open({
                        type: 1,
                        title: "项目详情",
                        area: ['60%', '80%'],
                        shadeClose: true,
                        shade: 0,
                        skin: 'layui-layer-rim',
                        content: $('#contentbox'),
                        cancel: function (index, res) {
                        }
                    });
                    break;
                case 'addrecord':
                    form.val("noteform", {
                        project_id: obj.data.id
                        , project_name: obj.data.name
                        , customer_id: obj.data.customer_id
                        , customer_name: obj.data.customer_name
                    });
                    var index = layer.open({
                        type: 1,
                        title: "添加记录",
                        area: ['60%', '80%'],
                        shadeClose: true,
                        shade: 0,
                        skin: 'layui-layer-rim',
                        content: $('#notebox'),
                        cancel: function (index, res) {
                        }
                    });
                    break;
                case 'recordlist':
                    getInfomsg(obj.data.id, '/getrecordlist');
                    var index = layer.open({
                        type: 1,
                        title: "沟通记录",
                        area: ['60%', '80%'],
                        shadeClose: true,
                        shade: 0,
                        skin: 'layui-layer-rim',
                        content: $('#timelinebox'),
                        cancel: function (index, res) {
                        }
                    });
                    break;
                case 'del':
                    layer.confirm('确定要删除该项目?', {icon: 3, title: '删除项目'}, function (index) {
                        $.ajax({
                            url: '/delproject',
                            data: {_token: "{{ csrf_token() }}", _method: 'DELETE', project_id: obj.data.id},
                            type: 'post',
                            dataType: 'json',
                            success: function (res) {
                                if (res.code == 200) {
                                    table.reload('projecttable', {});
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
                data: {project_id: cuid, actiontype: 'notlist'},
                type: "get",
                dataType: 'json',
                success: function (res) {
                    if (res.code == 200) {
                        if (url == '/getrecordlist') {
                            timelineContent(res.data);
                        } else {
                            formval(res.data);
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

        var formval = function (data) {
            form.val("projectform", {
                'editid': data.id
                , "name": data.name
                , "customer_id": data.customer_id
                , "admin_id": data.admin_id
                , "type_id": data.type_id
                , "status": data.status
                , "payment_status": data.payment_status
                , "isbid": data.isbid
                , "develop_date": data.develop_date
                , "deliver_date": data.deliver_date
                , "note": data.note
                , "remarks": data.remarks
                , "admin_id": data.admin_id
                , "createtime": data.create_time
                , "lasttime": data.last_time
            });
            layui.formSelects.value('input_type_id', !data.type_id ? [] : data.type_id );
        }

        var forvalrecord = function (data) {
            form.val("noteform", {
                project_id: data.project_id
                ,project_name: data.project_name
                ,customer_id: data.customer_id
                ,customer_name: data.customer_name
                ,result: data.result
                ,process: data.process
                ,question: data.question
                ,record_at: data.record_at
            });
        }


        var timelineContent = function (data) {
            var str = ''
            for (var i in data) {
                str += '<li class="layui-timeline-item">';
                str += '<i class="layui-icon layui-timeline-axis">&#xe63f;</i>';
                str += '<div class="layui-timeline-content layui-text">';
                str += '<h3 class="layui-timeline-title">' + data[i]['record_at'] + '<em>' + data[i]['customer_name'] + '</em> </h3>';
                str += '<p> <ul>';
                str += '<li> 录入人: ' + data[i]['input_name'] + '</li>';
                str += '<li> 沟通结果: ' + data[i]['result'] + '</li>';
                str += !data[i]['process'] ? '' : '<li> 沟通过程: ' + data[i]['process'] + '</li>' ;
                str += (!data[i]['question']) ? '' : '<li> 遗留问题: ' + data[i]['question'] + '</li>' ;
                str += '</ul>';
                str += '</p> </div> </li>';
            }
            str += '<li class="layui-timeline-item">';
            str += '<i class="layui-icon layui-timeline-axis">&#xe63f;</i>';
//            str += '<div class="layui-timeline-content layui-text">';
//            str += '<div class="layui-timeline-title">  </div>';
            str += '</div>';
            str += '</li>';
            $('#timelinebox').html(str);
                    return str;
        }


        $('#addProject').click(function () {
            formval({});
            $('.detailevent').css({display: 'none'});
            $('.editevent').css({display: 'block'});
            var index = layer.open({
                type: 1,
                title: "项目添加",
                area: ['60%', '80%'],
                shadeClose: true,
                shade: 0,
                skin: 'layui-layer-rim',
                content: $('#contentbox'),
                cancel: function (index, res) {

                }
            });
        });

        //监听提交
        form.on('submit(subform)', function (data) {
            $.ajax({
                url: '/addproject',
                data: data.field,
                type: "post",
                dataType: 'json',
                success: function (res) {
                    if (res.code == 200) {
                        layer.closeAll();
                        layer.msg("成功", {
                            icon: 1
                        });
                        table.reload('projecttable', {});
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

        form.on('submit(recordsubform)', function (data) {
            console.log(data);
            $.ajax({
                url: '/addrecord',
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
                        table.reload('projecttable', {});
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

</script>



@endsection
