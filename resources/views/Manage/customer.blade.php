@extends('Manage.layouts.app')

@section('content')
@parent

<fieldset class="layui-elem-field layui-field-title" style="margin-top: 10px;">
    <legend>{{$title}}</legend>
</fieldset>

<div class="demoTable">
    <form class="layui-form" action="" onsubmit="return false;">
        <div class="layui-inline">
            <input class="layui-input" name="username" id="demoReload" placeholder="客户名" >
        </div>
        <div class="layui-inline">
            <input class="layui-input" name="company" id="demoReload" placeholder="公司名">
        </div>
        <div class="layui-inline">
            <select name="admin_id" class="layui-input" lay-search>
                <option value="">业务员 输入文字筛选</option>
                <?php foreach (json_decode($data)->adminer as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="layui-inline">
            <input class="layui-input" name="phone" id="demoReload" placeholder="联系电话" >
        </div>
        <div class="layui-inline">
            <select name="source" class="layui-input" >
                <option value="">来源</option>
                <?php foreach (json_decode($data)->source as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="layui-inline">
            <select name="gender" class="layui-input" >
                <option value="">性别</option>
                <?php foreach (config('manage.gender') as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="layui-inline">
            <input class="layui-input" name="create_time" id="formdate" placeholder="创建时间" >
        </div>
        <button class="layui-btn" data-type="reload" lay-submit lay-filter="formDemo">搜索</button>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <button class="layui-btn" data-type="reload" id="addCustomer">添加客户</button>
    </form>
</div>

<table id="demo" lay-filter="test"></table>

<script type="text/html" id="barDemo">
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


@include('Manage.layouts.customer_form')



<ul class="layui-timeline" id='timelinebox' style='display: none; margin: 10px 20px;'></ul>


<script>
    layui.use(['table', 'form', 'laydate', 'jquery'], function () {
        var table = layui.table, $ = layui.jquery, form = layui.form, laydate = layui.laydate;
        var sysdata = <?php echo $data ?>;
        var sources = sysdata['source'];

        //第一个实例
        table.render({
            elem: '#demo'
            , id: 'customertable'
            , url: '/customerlist' //数据接口
            , page: true //开启分页
            , cellMinWidth: 60
            , cols: [[//表头
                    {field: 'id', title: 'ID', width: 60, sort: true, fixed: 'left'}
                    , {field: 'username', title: '客户名', width: 80}
                    , {field: 'create_time', title: '录入日期', width: 170}
                    , {field: 'phone', title: '联系方式', width: 130}
                    , {field: 'company', title: '公司名', }
                    , {field: 'position', title: '职位', width: 100}
                    , {field: 'type', title: '类型', templet: '#types', width: 120}
                    , {field: 'total', title: '项目数', width: 80, sort: true, templet: function (d) {
                            if (d.total > 0) {
                                return '<a class="layui-btn layui-btn-xs" lay-event="project"> &nbsp;&nbsp;' + d.total + ' &nbsp;&nbsp;</a>';
                            } else {
                                return 0;
                            }
                        }}
                    , {field: 'is_new_customer', title: '老客户', templet: function (d) {
                            return d.is_new_customer == 1 ? '是' : '否';
                        }, width: 80}
                    , {field: 'source', title: '来源', templet: function (d) {
                            return  sources[d.source];
                        }, width: 80}
                    , {fixed: 'right', title: '操作', toolbar: '#barDemo', width: 160}
                ]]
            , response: {
//                statusName: 'status', //规定数据状态的字段名称，默认：code
                statusCode: 200 //规定成功的状态码，默认：0
//                , msgName: 'hint' //规定状态信息的字段名称，默认：msg
                , countName: 'total' //规定数据总数的字段名称，默认：count
//                , dataName: 'rows' //规定数据列表的字段名称，默认：data
            }
        });

        laydate.render({
            elem: '#formdate' //指定元素
            , range: '@'
        });


        //监听提交
        form.on('submit(formDemo)', function (data) {
//            layer.msg(JSON.stringify(data.field));
            table.reload('customertable', {
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
                    getInfomsg(obj.data.id, '/getcustomer');
                    $('.detailevent').css({display: 'none'});
                    $('.editevent').css({display: 'block'});
                    var index = layer.open({
                        type: 1,
                        title: "客户信息编辑",
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
                    getInfomsg(obj.data.id, '/getcustomer');
                    $('.detailevent').css({display: 'block'});
                    $('.editevent').css({display: 'none'});
                    var index = layer.open({
                        type: 1,
                        title: "客户详情信息",
                        area: ['60%', '80%'],
                        shadeClose: true,
                        shade: 0,
                        skin: 'layui-layer-rim',
                        content: $('#contentbox'),
                        cancel: function (index, res) {
                        }
                    });
                    break;
                case 'project':
                    getInfomsg(obj.data.id, '/getprojects');
                    var index = layer.open({
                        type: 1,
                        title: "合作项目",
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
                    layer.confirm('确定要删除该用户?', {icon: 3, title: '删除用户'}, function(index){
                        $.ajax({
                            url: '/delcustomer',
                            data: {_token: "{{ csrf_token() }}", _method: 'DELETE', customer_id: obj.data.id},
                            type: 'post',
                            dataType: 'json',
                            success: function(res){
                                if(res.code == 200){
                                    table.reload('customertable', {});
                                }else{
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
                data: {customer_id: cuid},
                type: "get",
                dataType: 'json',
                success: function (res) {
                    if (res.code == 200) {
                        if (url == '/getprojects') {
                            console.log(res);
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
            form.val("customerform", {
                'editid': data.id
                , "username": data.username
                , "type": data.type
                , "gender": data.gender
                , "is_new_customer": data.is_new_customer
                , "source": data.source
                , "company": data.company
                , "address": data.address
                , "phone": data.phone
                , "landline": data.landline
                , "wechat": data.wechat
                , "position": data.position
                , "remarks": data.remarks
                , "adminer": data.admin_name
                , "createtime": data.create_time
                , "lasttime": data.last_time
            });
        }

        var timelineContent = function (data) {
            var str = ''
            for (var i in data) {
                str += '<li class="layui-timeline-item">';
                str += '<i class="layui-icon layui-timeline-axis">&#xe63f;</i>';
                str += '<div class="layui-timeline-content layui-text">';
                str += '<h3 class="layui-timeline-title">' + data[i]['created_date'] + '<em>' + data[i]['name'] + '</em> </h3>';
                str += '<p> <ul>';
                str += '<li> 类型: ' + data[i]['type_id'] + '</li>';
                str += '<li> 项目进度: ' + data[i]['status'] + '</li>';
                str += !data[i]['deliver_date'] ? '' : '<li> 交付日期: ' + data[i]['deliver_date'] + ' &nbsp;&nbsp; 剩余'+ data[i]['surplus'] +'天'+ '</li>';
                str += !data[i]['remarks'] ? '' : '<p> 项目说明: ' + data[i]['remarks'] + ' </p>';
                str += !data[i]['note'] ? '' : '<p> 项目需求: ' + data[i]['note'] + ' </p>';
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

        $('#addCustomer').click(function () {
            formval({});
            $('.detailevent').css({display: 'none'});
            $('.editevent').css({display: 'block'});
            var index = layer.open({
                type: 1,
                title: "客户信息编辑",
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
//                    layer.msg(JSON.stringify(data.field));
            if (!data.field.username) {
                layer.msg('客户名不能为空!');
                return false;
            }
            if (!data.field.phone && !data.field.wechat && !data.field.landline) {
                layer.msg('联系电话、座机、微信号至少填一个!');
                return false;
            }
            $.ajax({
                url: '/addcustomer',
                data: data.field,
                type: "post",
                dataType: 'json',
                success: function (res) {
                    if (res.code == 200) {
                        layer.closeAll();
                        layer.msg("成功", {
                            icon: 1
                        });
                        table.reload('customertable', {});
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
