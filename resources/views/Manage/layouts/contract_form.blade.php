<div id='contractbox' style='display: none; margin: 10px 20px;'>
    <form class="layui-form  layui-form-pane" enctype="multipart/form-data" lay-filter="contractform" onsubmit="return false;">
        <input type='hidden' name='editid'>
        <input type='hidden' name='project_id'>
        <input type='hidden' name='url'>
         
        <div class="layui-form-item">
            <label class="layui-form-label">  <em style='color:red;'>*</em>  合同标题 </label>
            <div class="layui-input-block">
                <input class="layui-input" lay-verify="required" name="title" >
            </div>
        </div> 
        
        <div class="layui-form-item">
            <label class="layui-form-label">  <em style='color:red;'>*</em>  项目</label>
            <div class="layui-input-inline">
                <select name="project_id" lay-verify="required"  class="layui-input" >
                    <option value="">选择签约项目</option>
                    <?php foreach (json_decode($data)->project as $key => $val) { ?>
                        <option value="<?php echo $key ?>"><?php echo $val ?></option>
                    <?php } ?>
                </select>
            </div>
            
            <label class="layui-form-label">  <em style='color:red;'>*</em>  合同金额 </label>
            <div class="layui-input-inline">
                <input class="layui-input" name="money" lay-verify="required" >
            </div>
        </div>  
 
        <div class="layui-form-item"> 
            <label class="layui-form-label">签约日期</label>
            <div class="layui-input-inline">     
                <input class="layui-input formdate"  name="take_effect_time" placeholder="" >
            </div>
            
            <label class="layui-form-label">生效日期</label>
            <div class="layui-input-inline">     
                <input class="layui-input formdate" name="contract_time" placeholder="" >
            </div>
        </div>
        <div class="layui-form-item"> 
            <label class="layui-form-label">截止日期</label>
            <div class="layui-input-inline">     
                <input class="layui-input formdate"  name="end_time" placeholder="" >
            </div>
            
            <div class="layui-input-inline" style='min-width: 300px;'>     
                <button class="layui-btn" id='upcontract' ><i class="layui-icon">&#xe67c;</i>选择合同文件</button>
            </div>
        </div>
        
        <div class="layui-form-item"> 
            <div class="">
                <textarea name="describe" placeholder="合同备注" class="layui-textarea"></textarea>
            </div>
        </div>  

        <div class="layui-form-item  editevent">
            <div class="layui-input-block">
                {{ csrf_field() }}
                <button class="layui-btn" lay-submit="" id='subform' lay-filter="contractform">立即提交</button>
            </div>
        </div>
    </form>
</div>