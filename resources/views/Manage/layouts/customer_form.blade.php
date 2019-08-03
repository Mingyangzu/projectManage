<div id='contentbox' style='display: none; margin: 10px 20px;'>
    <form class="layui-form  layui-form-pane" lay-filter="customerform" onsubmit="return false;">
        <input type='hidden' name='editid'>

        <div class="layui-form-item">
            <label class="layui-form-label"> <em style='color:red;'>*</em> 客户名</label>
            <div class="layui-input-inline" >
                <input class="layui-input" lay-verify="required" name="username" placeholder="" >
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">性别</label>
            <div class="layui-input-inline" style='border: 1px solid #e6e6e6;height: 36px;'>
                <input  type="radio" name="gender" value="0" title="女" checked>
                <input type="radio" name="gender" value="1" title="男" >
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
            <label class="layui-form-label"> <em style='color:red;'>*</em> 联系电话</label>
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

        <div class="layui-form-item editdetailevent" style='display:none'> 
            <label class="layui-form-label">录入人</label>
            <div class="layui-input-inline">
                <select name="admin_id" class="layui-input" lay-search>
                <option value="">录入人</option>
                <?php foreach (json_decode($data)->adminer as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
            </select>
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
                <button class="layui-btn" id="subform" lay-submit="" lay-filter="subform">立即提交</button>
            </div>
        </div>
    </form>
</div>