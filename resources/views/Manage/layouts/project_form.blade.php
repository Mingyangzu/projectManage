<div id='contentbox' style='display: none; margin: 10px 20px;'>
    <form class="layui-form  layui-form-pane" lay-filter="projectform" onsubmit="return false;">
        <input type='hidden' name='editid'>

        <div class="layui-form-item">
            <label class="layui-form-label"> <em style='color:red;'>*</em> 项目名</label>
            <div class="layui-input-block">
                <input class="layui-input" lay-verify="required" name="name" placeholder="">
            </div>
        </div>        

        <div class="layui-form-item"> 
            <label class="layui-form-label"> <em style='color:red;'>*</em> 客户</label>
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
                <select name="admin_id"  class="layui-input" >
                    <option value="">业务员</option>
                    <?php foreach (json_decode($data)->adminer as $key => $val) { ?>
                        <option value="<?php echo $key ?>"><?php echo $val ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label"> <em style='color:red;'>*</em> 项目类型</label>
            <div class="layui-input-block" style="400px;">
                <select lay-verify="required" name="type_id" class="layui-input" xm-select="input_type_id">
                    <option value="">类型</option>
                    <?php foreach (json_decode($data)->type as $key => $val) { ?>
                        <option value="<?php echo $val ?>"><?php echo $val ?></option>
                    <?php } ?>
                </select>
            </div>
            
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label"> <em style='color:red;'>*</em> 项目状态</label>
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
                <select name="payment_status"  class="layui-input" >
                    <option value="">财务状态</option>
                    <?php foreach (json_decode($data)->pay_status as $key => $val) { ?>
                        <option value="<?php echo $key ?>"><?php echo $val ?></option>
                    <?php } ?>
                </select>
            </div>

            <label class="layui-form-label">投标项目</label>
            <div class="layui-input-inline">
                <select name="is_bid"  class="layui-input" >
                    <option value="0">否</option>
                    <option value="0">是</option>
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
                <textarea name="note" placeholder="需求描述" lay-verify="required"  class="layui-textarea"></textarea>
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