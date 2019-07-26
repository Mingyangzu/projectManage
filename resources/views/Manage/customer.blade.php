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
    </form>
</div>

<table id="demo" lay-filter="test"></table>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="project">项目</a>
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-xs" lay-event="detail">详情</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="edit">指派业务员</a>
</script>

<script type="text/html" id="types"> 
    @{{# if(d.type == 1){  }}
    企业
    @{{# }else{ }}
    个人
    @{{# } }}
</script>



<div id='contentbox' style='display: onne;'>
    <form class="layui-form" action="/addcustomer" >
        <input type='hidden' name='editid'>

        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-inline">
                <input class="layui-input" name="username" id="demoReload" placeholder="客户名" >
            </div>
            <div class="layui-input-inline">
            <input class="layui-input" name="company" id="demoReload" placeholder="公司名">
            </div>
        </div>

        <div class="layui-form-item">
        <div class="layui-input-inline">
            <input class="layui-input" name="phone" id="demoReload" placeholder="类型" >
        </div>
        <div class="layui-input-inline">
            <input class="layui-input" name="phone" id="demoReload" placeholder="联系电话" >
        </div>
        </div>
        
        <div class="layui-form-item">
        <div class="layui-input-inline">
            <input class="layui-input" name="phone" id="demoReload" placeholder="座机" >
        </div>
        <div class="layui-input-inline">
            <input class="layui-input" name="phone" id="demoReload" placeholder="职位" >
        </div>
        </div>    
                <div class="layui-form-item">    
        <div class="layui-input-inline">
            <input class="layui-input" name="phone" id="demoReload" placeholder="公司地址" >
        </div>
        <div class="layui-input-inline">
            <select name="source" class="layui-input" >
                <option value="">来源</option>
                <?php foreach (json_decode($data)->source as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
            </select>
        </div>
       </div>             
        
        
         <div class="layui-form-item">    
        <div class="layui-input-inline">     
            <input class="layui-input" name="phone" id="demoReload" placeholder="业务员" >
        </div>
        <div class="layui-input-inline">
            <input class="layui-input" name="phone" id="demoReload" placeholder="备注" >
        </div>
        </div>   

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="addres">立即提交</button>
            </div>
        </div>
    </form>
</div>







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
//            console.log(data.field);
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
                    console.log(obj);
                    var level2 = [];
                    level2[0] = {'id': obj.data.fid, 'name': obj.data.title};

                    var index = layer.open({
                        type: 1,
                        title: "客户信息编辑",
                        area: ['60%', '80%'],
//                        fix: false,
//                        maxmin: true,
                        shadeClose: true,
                        shade: 0,
                        skin: 'layui-layer-rim',
                        content: $('#contentbox'),
                        cancel: function (index, res) {
//                            $('#contentbox').css({'display': 'none'});

                        }
                    });
                    break;
            }
        });


    });
</script>



@endsection
