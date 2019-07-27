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
            <input class="layui-input" name="create_time" id="formdate" placeholder="创建时间" >
        </div>
        <button class="layui-btn" data-type="reload" lay-submit lay-filter="formDemo">搜索</button>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <button class="layui-btn" data-type="reload" id="addCustomer">添加客户</button>
    </form>
</div>

<table id="demo" lay-filter="test"></table>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="project">项目</a>
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-xs" lay-event="detail">详情</a>
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
            <label class="layui-form-label">客户名</label>
            <div class="layui-input-inline" >
                <input class="layui-input" lay-verify="required" name="username" placeholder="" >
            </div>

            <label class="layui-form-label">老客户</label>
            <div class="layui-input-inline" style='border: 1px solid #e6e6e6;height: 36px;'>
                <input  type="radio" name="is_new_customer" value="0" title="否" checked>
                <input type="radio" name="is_new_customer" value="1" title="是">
            </div>
        </div>

        <div class="layui-form-item" >
            <label class="layui-form-label">类型</label>
            <div class="layui-input-inline">
                <select name="type" class="layui-input" >
                    <option value="">类型</option>
                    <?php foreach (json_decode($data)->type as $key => $val) { ?>
                        <option value="<?php echo $key ?>"><?php echo $val ?></option>
                    <?php } ?>
                </select>
            </div>

            <label class="layui-form-label">来源</label>
            <div class="layui-input-inline">
                <select name="source" class="layui-input" >
                    <option value="">未知</option>
                    <?php foreach (json_decode($data)->source as $k => $v) { ?>
                        <option value="<?php echo $k ?>"><?php echo $v ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">公司名</label>
            <div class="layui-input-block">
                <input class="layui-input" name="company" placeholder="">
            </div>
        </div>        

        <div class="layui-form-item"> 
            <label class="layui-form-label">公司地址</label>
            <div class="layui-input-block">
                <input class="layui-input" name="address" id="demoReload" placeholder="" >
            </div>
        </div>

        <div class="layui-form-item"  style=''>
            <label class="layui-form-label">联系电话</label>
            <div class="layui-input-inline">
                <input class="layui-input" lay-verify="required|phone" name="phone" id="demoReload" placeholder="" >
            </div>
            <label class="layui-form-label">座机</label>
            <div class="layui-input-inline">
                <input class="layui-input" name="landline" id="demoReload" placeholder="" >
            </div>
        </div>

        <div class="layui-form-item" >
            <label class="layui-form-label">微信号</label>
            <div class="layui-input-inline">
                <input class="layui-input" name="wechat" id="demoReload" placeholder="" >
            </div>

            <label class="layui-form-label">职位</label>
            <div class="layui-input-inline">
                <input class="layui-input" name="position" id="demoReload" placeholder="" >
            </div>
        </div>

        <div class="layui-form-item detailevent" style='display:none'> 
            <label class="layui-form-label">录入人</label>
            <div class="layui-input-inline">     
                <input class="layui-input" name="adminer" id="demoReload" placeholder="" >
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

        <div class="layui-form-item"> 
            <div class="">
                <textarea name="remarks" placeholder="备注信息" class="layui-textarea"></textarea>
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




<ul class="layui-timeline" id='timelinebox' style='display: none; margin: 10px 20px;'>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
    <div class="layui-timeline-content layui-text">
        <h3 class="layui-timeline-title">2019-06-18  <b>潮鹏健康管理系统</b> </h3>
      <p>
        类型/状态/业务员/合同截止时间/描述
        <br>
        <br>无论它能走多远，抑或如何支撑？至少我曾倾注全心，无怨无悔 <i class="layui-icon"></i>
      </p>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
    <div class="layui-timeline-content layui-text">
      <h3 class="layui-timeline-title">8月16日</h3>
      <p>杜甫的思想核心是儒家的仁政思想，他有“<em>致君尧舜上，再使风俗淳</em>”的宏伟抱负。个人最爱的名篇有：</p>
      <ul>
        <li>《登高》</li>
        <li>《茅屋为秋风所破歌》</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
    <div class="layui-timeline-content layui-text">
      <h3 class="layui-timeline-title">8月15日</h3>
      <p>
        中国人民抗日战争胜利72周年
        <br>常常在想，尽管对这个国家有这样那样的抱怨，但我们的确生在了最好的时代
        <br>铭记、感恩
        <br>所有为中华民族浴血奋战的英雄将士
        <br>永垂不朽
      </p>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
    <div class="layui-timeline-content layui-text">
      <div class="layui-timeline-title">过去</div>
    </div>
  </li>
</ul>




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
                    , {field: 'company', title: '公司名', }
                    , {field: 'position', title: '职位', width: 100}
                    , {field: 'type', title: '类型', templet: '#types', width: 60}
                    , {field: 'phone', title: '联系方式', width: 130}
                    , {field: 'total', title: '项目数', width: 80, sort: true}
                    , {field: 'is_new_customer', title: '老客户', templet: function (d) {
                            return d.is_new_customer == 1 ? '是' : '否';
                        }, width: 80}
                    , {field: 'source', title: '来源', templet: function (d) {
                            return  sources[d.source];
                        }, width: 80}
                    , {field: 'create_time', title: '创建时间', width: 120}
                    , {title: '操作', toolbar: '#barDemo', width: 150}
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
                    getInfomsg(obj.data.id, '/getcontract');
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
                        if(url == '/getcontract'){
                            console.log(res);
                        }else{
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
