<div id='editbox' style='display: none; margin: 10px 20px;'>
    <form class="layui-form  layui-form-pane" lay-filter="processform" onsubmit="return false;">
        <input type='hidden' name='editid'>
        <div class="layui-form-item">
            <label class="layui-form-label"> <em style='color:red;'>*</em> 项目 </label>
            <div class="layui-input-inline"  style="width: 300px;">
                <select name="project_id" class="layui-input"  lay-search lay-filter="project_id" lay-verify="required">
                <option value="">项目</option>
                <?php foreach (json_decode($data)->contract_project as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
                </select>
            </div>
            
            <label class="layui-form-label"> <em style='color:red;'>*</em> 签单人 </label>
            <div class="layui-input-inline">
                <select name="salesman_id" class="layui-input" lay-search lay-verify="required">
                <option value="">签单人</option>
                <?php foreach (json_decode($data)->adminer as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
                </select>
            </div>
        </div>   
        <div class="layui-form-item">
            <label class="layui-form-label" style="width: 160px;"> <em style='color:red;'>*</em> 下单日期 </label>
            <div class="layui-input-inline">
                <input class="layui-input formdate" name="develop_date" lay-verify="required" lay-filter="develop_date">
            </div>
            
            <label class="layui-form-label" style="width: 160px;"> <em style='color:red;'>*</em> 合同签订完结日期 </label>
            <div class="layui-input-inline">
                <input class="layui-input formdate" name="deliver_date" lay-verify="required" lay-filter="deliver_date">
            </div>
        </div> 
        
        <div class="layui-form-item">
            <label class="layui-form-label" style="width: 160px;"> <em style='color:red;'>*</em> 项目技术负责人 </label>
            <div class="layui-input-inline">
                <select name="technical_id" class="layui-input" lay-search lay-verify="required">
                <option value="">项目技术负责人</option>
                <?php foreach (json_decode($data)->adminer as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
                </select>
            </div>
            
            <label class="layui-form-label" style="width: 160px;"> <em style='color:red;'>*</em> 项目监督负责人 </label>
            <div class="layui-input-inline">
                <select name="admin_id" class="layui-input" lay-search  lay-verify="required">
                <option value="">项目监督负责人</option>
                <?php foreach (json_decode($data)->adminer as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
                </select>
            </div>
        </div> 

        <div class="layui-form-item">
        <label class="layui-form-label" style="width: 160px;"> <em style='color:red;'>*</em> 当前开发进度 </label>
            <div class="layui-input-inline">
                <select name="status" class="layui-input" lay-search lay-verify="required">
                <?php foreach (json_decode($data)->status as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
                </select>
            </div>
        
        <label class="layui-form-label" style="width: 160px;"> <em style='color:red;'>*</em> 下一执行人 </label>
            <div class="layui-input-inline">
                <select name="develop_id" class="layui-input" lay-search lay-verify="required">
                <option value="">执行人</option>
                <?php foreach (json_decode($data)->adminer as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
                </select>
            </div>
        </div> 
        
        <div class="layui-form-item">
            <label class="layui-form-label" style="width: 160px;"> 客户姓名及电话 </label>
            <div class="layui-input-inline">
                <input class="layui-input" name="customer_str" lay-filter="customer_name"  style="width: 300px;">
            </div>
        </div> 
        
        <div class="layui-form-item">
            <label class="layui-form-label" style="width: 160px;"> 公司名称及座机 </label>
            <div class="layui-input-inline">
                <input class="layui-input" name="company_str"  lay-filter="company_name"  style="width: 300px;">
            </div>
        </div> 

        <div class="layui-form-item"> 
            <div class="">
                <textarea name="note" placeholder="项目开发内容及需求" lay-filter="note" class="layui-textarea"></textarea>
            </div>
        </div>  

        <div class="layui-form-item  editevent">
            <div class="layui-input-block">
                {{ csrf_field() }}
                <button class="layui-btn" id="subform" lay-submit="" lay-filter="subform">添加</button>
            </div>
        </div>
    </form>
</div>

<script>
    layui.use(['form', 'laydate', 'jquery'], function () {
        var $ = layui.jquery, form = layui.form, laydate = layui.laydate;
        
        form.on('select(project_id)', function(data){
            $.ajax({
                    url: '/process/getproject',
                    data: {project_id: data.value},
                    type: "get",
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 200) {
                            processformrender(res.data);
                        } else {
                            layer.msg(res.msg, {
                                icon: 5
                            });
                            processformrender({});
                            return false;
                        }
                    }
                });
        });
        
        var processformrender = function(data){
            data.customer_str = '';
            data.company_str = '';
            data.customer_str += data.customer_name ? data.customer_name : '';
            data.customer_str += data.phone ? data.phone : '';
            data.company_str += data.company ? data.company : '';
            data.company_str += data.landline ? data.landline : '';
            form.val("processform", {
                project_id: data.id
                , salesman_id: data.admin_id
                , develop_date: data.contract_time
                , deliver_date: data.end_time
                , customer_str: data.customer_str
                , company_str: data.company_str
                , note: data.note
            });
        }
        
    });
    
</script>
