<div id='addnote' style='display: none; margin: 10px 20px;'>
    <form class="layui-form  layui-form-pane" lay-filter="addnoteform" onsubmit="return false;">
        <input type="hidden" name="editid" lay-filter="editid">
        <input type="hidden" name="process_id" lay-filter="process_id">
        <div class="layui-form-item">
            <label class="layui-form-label"> 项目 </label>
            <div class="layui-input-inline"  style="width: 300px;">
                <input class="layui-input" name="process_name" readonly="true" lay-filter="process_name">
            </div>
        </div>   
        <div class="layui-form-item">
            <label class="layui-form-label" style="width: 160px;"> <em style='color:red;'>*</em> 指定完成日期 </label>
            <div class="layui-input-inline">
                <input class="layui-input formdate" name="over_date" lay-verify="required" lay-filter="over_date">
            </div>
            
            <label class="layui-form-label" style="width: 160px;"> <em style='color:red;'>*</em> 实际完成日期 </label>
            <div class="layui-input-inline">
                <input class="layui-input formdate" name="end_date" lay-verify="required" lay-filter="end_date">
            </div>
        </div> 
        
        <div class="layui-form-item detailevent">
        <label class="layui-form-label" style="width: 110px;"> <em style='color:red;'>*</em> 下一阶段 </label>
            <div class="layui-input-inline">
                <select name="status" class="layui-input" lay-verify="required">
                <option value=""></option>
                <?php foreach (json_decode($data)->status as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
                </select>
            </div>
            
        <label class="layui-form-label" style="width: 120px;"> 下一执行人 </label>
            <div class="layui-input-inline">
                <select name="develop_id" class="layui-input" lay-verify="required">
                <option value="">执行人</option>
                <?php foreach (json_decode($data)->adminer as $k => $v) { ?>
                    <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
                </select>
            </div>
        </div> 

        <div class="layui-form-item"> 
            <div class=""><em style='color:red;'>*</em>开发内容
                <textarea name="remarks" placeholder="" lay-filter="remarks" class="layui-textarea" lay-verify="required"></textarea>
            </div>
        </div> 
        <div class="layui-form-item"> 
            <div class=""><em style='color:red;'>*</em>开发人员总结
                <textarea name="note" placeholder="" lay-filter="note" class="layui-textarea" lay-verify="required"></textarea>
            </div>
        </div>

        <div class="layui-form-item  editevent">
            <div class="layui-input-block">
                {{ csrf_field() }}
                <button class="layui-btn" id="subform" lay-submit="" lay-filter="subform">提交开发记录</button>
            </div>
        </div>
    </form>
</div>

