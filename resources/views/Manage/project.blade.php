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
            <input class="layui-input" name="customer_name" id="demoReload" placeholder="客户名">
        </div>
        <!--        <div class="layui-inline">
                    <input class="layui-input" name="phone" id="demoReload" placeholder="联系电话">
                </div>-->
        <div class="layui-inline">
            <input class="layui-input" name="admin_name" id="demoReload" placeholder="业务员">
        </div>
        <div class="layui-inline">
            <select name="type_id" class="layui-input" >
                <option value="">项目类型</option>
                <?php foreach (json_decode($data)->type as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="layui-input-inline">
            <select name="status" class="layui-input" >
                <option value="">项目进度</option>
                <?php foreach (json_decode($data)->status as $key => $val) { ?>
                    <option value="<?php echo $key ?>"><?php echo $val ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="layui-input-inline">
            <select name="payment_status" class="layui-input" >
                <option value="">财务状态</option>
                <?php foreach (json_decode($data)->pay_status as $key => $val) { ?>
                    <option value="<?php echo $key ?>"><?php echo $val ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="layui-inline">
            <input class="layui-input" name="deliver_date" id="formdate" placeholder="交付日期" >
        </div>
        <button class="layui-btn" data-type="reload" lay-submit lay-filter="formDemo">搜索</button>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <button class="layui-btn" data-type="reload" id="addCustomer">添加项目</button>
    </form>
</div>

<table id="demo" lay-filter="test"></table>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="addnote">添加记录</a>
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



<div id='contentbox' style='display: none; margin: 10px 20px;'>
    <form class="layui-form  layui-form-pane" lay-filter="customerform" onsubmit="return false;">
        <input type='hidden' name='editid'>

        <div class="layui-form-item">
            <label class="layui-form-label">项目名</label>
            <div class="layui-input-block">
                <input class="layui-input" lay-verify="required" name="name" placeholder="">
            </div>
        </div>        

        <div class="layui-form-item"> 
            <label class="layui-form-label">客户</label>
            <div class="layui-input-inline">
                <select lay-verify="required" name="customer_id" lay-verify="required" class="layui-input" >
                    <option value="">客户</option>
                    <?php foreach (json_decode($data)->customer as $key => $val) { ?>
                        <option value="<?php echo $key ?>"><?php echo $val ?></option>
                    <?php } ?>
                </select>
            </div>
            
            <label class="layui-form-label">指派业务员</label>
            <div class="layui-input-inline">  
                <select name="admin_id" lay-verify="required" class="layui-input" >
                    <option value="">业务员</option>
                    <?php foreach (json_decode($data)->adminer as $key => $val) { ?>
                        <option value="<?php echo $key ?>"><?php echo $val ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">项目类型</label>
            <div class="layui-input-inline">
                <select lay-verify="required" name="type_id" class="layui-input" >
                    <option value="">类型</option>
                    <?php foreach (json_decode($data)->type as $key => $val) { ?>
                        <option value="<?php echo $key ?>"><?php echo $val ?></option>
                    <?php } ?>
                </select>
            </div>
            <label class="layui-form-label">项目状态</label>
            <div class="layui-input-inline">
                <select name="status" lay-verify="required"  class="layui-input" >
                    <option value="">项目状态</option>
                    <?php foreach (json_decode($data)->status as $key => $val) { ?>
                        <option value="<?php echo $key ?>"><?php echo $val ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="layui-form-item" >
            <label class="layui-form-label">财务状态</label>
            <div class="layui-input-inline">
                <select name="payment_status" lay-verify="required" class="layui-input" >
                    <option value="">财务状态</option>
                    <?php foreach (json_decode($data)->pay_status as $key => $val) { ?>
                        <option value="<?php echo $key ?>"><?php echo $val ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="layui-form-item"> 
            <label class="layui-form-label">开发日期</label>
            <div class="layui-input-inline">
                <input class="layui-input formdate" name="develop_date" id="developdate" placeholder="" >
            </div>
            <label class="layui-form-label">交付日期</label>
            <div class="layui-input-inline">     
                <input class="layui-input formdate" name="deliver_date" id="deliverdate" placeholder="" >
            </div>
        </div> 

        <div class="layui-form-item"> 
            <div class="">
                <textarea name="note" placeholder="需求描述" class="layui-textarea"></textarea>
            </div>
        </div>  

        <div class="layui-form-item"> 
            <div class="">
                <textarea name="remarks" placeholder="项目说明" class="layui-textarea"></textarea>
            </div>
        </div> 

        <div class="layui-form-item detailevent" style='display:none'> 
            <label class="layui-form-label">添加时间</label>
            <div class="layui-input-inline">     
                <input class="layui-input" name="createtime" id="demoReload" placeholder="" >
            </div>
            <label class="layui-form-label">更新时间</label>
            <div class="layui-input-inline">     
                <input class="layui-input" name="lasttime" id="demoReload" placeholder="" >
            </div>
        </div>

        <div class="layui-form-item  editevent">
            <div class="layui-input-block">
                {{ csrf_field() }}
                <button class="layui-btn" lay-submit="" lay-filter="subform">立即提交</button>
            </div>
        </div>
    </form>
</div>

<ul class="layui-timeline" id='timelinebox' style='display: none; margin: 10px 20px;'></ul>

<script>
    layui.use(['table', 'form', 'laydate', 'jquery'], function () {
        var table = layui.table, $ = layui.jquery, form = layui.form, laydate = layui.laydate;
        var sysdata = <?php echo $data ?>;

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
                    , {field: 'admin_name', title: '业务员', width: 100}
                    , {field: 'total', title: '沟通记录数', width: 100, templet: function (d) {
                            if (d.total > 0) {
                                return '<a class="layui-btn layui-btn-xs" lay-event="project"> &nbsp;&nbsp;' + d.total + ' &nbsp;&nbsp;</a>';
                            } else {
                                return 0;
                            }
                        }}
                    , {field: 'type_id', title: '类型', templet: '#types', width: 80, templet: function (d) {
                            return sysdata['type'][d.type_id];
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
        lay('.formdate').each(function(){
    laydate.render({
      elem: this
      ,trigger: 'click'
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
                    getInfomsg(obj.data.id, '/getcustomer');
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
                    getInfomsg(obj.data.id, '/getcustomer');
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
                case 'addnote':
                    getInfomsg(obj.data.id, '/getcontract');
                    var index = layer.open({
                        type: 1,
                        title: "添加记录",
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
                    console.log(obj.data);
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
                        if (url == '/getcontract') {
                            console.log(res);
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
                str += '<h3 class="layui-timeline-title">' + data[i]['create_time'] + '<em>' + data[i]['name'] + '</em> </h3>';
                str += '<p> <ul>';
                str += '<li> 类型: ' + data[i]['type_id'] + '</li>';
                str += '<li> 项目进度: ' + data[i]['status'] + '</li>';
                str += '<li> 交付日期: ' + data[i]['deliver_date'] + '</li>';
                str += '<p> ' + data[i]['remarks'] + ' </p>';
                str += '</ul>';
                str += '</p> </div> </li>';
            }
            str += '<li class="layui-timeline-item">';
            str += '<i class="layui-icon layui-timeline-axis">&#xe63f;</i>';
            str += '<div class="layui-timeline-content layui-text">';
            str += '<div class="layui-timeline-title">  </div>';
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
