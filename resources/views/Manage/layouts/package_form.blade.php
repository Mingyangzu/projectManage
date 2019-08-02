<div id='contractbox' style='display: none; margin: 10px 20px;'>
    <form class="layui-form  layui-form-pane" enctype="multipart/form-data" lay-filter="contractform" onsubmit="return false;">
        <input type='hidden' name='editid'>
        <input type='hidden' name='package_app'>
        <input type='hidden' name='package_web'>
        <input type='hidden' name='package_sql'>
        <input type='hidden' name='size_app'>
        <input type='hidden' name='size_web'>
        <input type='hidden' name='size_sql'>
         
        
        <div class="layui-form-item">
            <label class="layui-form-label">  <em style='color:red;'>*</em> 项目</label>
            <div class="layui-input-block">
                <select name="project_id" lay-verify="required"  class="layui-input"  lay-search>
                    <option value="">选择项目</option>
                    <?php foreach (json_decode($data)->project as $key => $val) { ?>
                        <option value="<?php echo $key ?>"><?php echo $val ?></option>
                    <?php } ?>
                </select>
            </div>
            
        </div>  
 
        <div class="layui-form-item"> 
            <div class="layui-input-inline" style='min-width: 200px;'>     
                <button class="layui-btn uppackages" id="app"><i class="layui-icon" >&#xe67c;</i>上传APP包文件</button>
            </div>
            
            <div class="layui-input-inline" style='min-width: 200px;'>     
                <button class="layui-btn uppackages" id="web"><i class="layui-icon">&#xe67c;</i>上传web包文件</button>
            </div>
            
            <div class="layui-input-inline" style='min-width: 200px;'>     
                <button class="layui-btn uppackages" id="sql"><i class="layui-icon">&#xe67c;</i>上传SQL包文件</button>
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
                <button class="layui-btn" lay-submit="" id='subform' lay-filter="contractform">立即提交</button>
            </div>
        </div>
    </form>
</div>