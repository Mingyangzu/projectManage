<div id='notebox' style='display: none; margin: 10px 20px;'>
    <form class="layui-form  layui-form-pane" lay-filter="noteform" onsubmit="return false;">
        <input type='hidden' name='editid'>
        <input type='hidden' name='project_id'>
        <input type='hidden' name='customer_id'>
        <div class="layui-form-item">
            <label class="layui-form-label"> 项目名</label>
            <div class="layui-input-block">
                <input class="layui-input" name="project_name" readonly="">
            </div>
        </div>   
        <div class="layui-form-item">
            <label class="layui-form-label"> 客户名</label>
            <div class="layui-input-block">
                <input class="layui-input" name="customer_name" readonly="" >
            </div>
        </div>   

        <div class="layui-form-item"> 
            <div class="">
                <textarea name="process" placeholder="沟通过程" class="layui-textarea"></textarea>
            </div>
        </div>  

        <div class="layui-form-item"> 
            <div class="">
                <textarea name="result" placeholder="沟通结果" lay-verify="required" class="layui-textarea"></textarea>
            </div>
        </div> 

        <div class="layui-form-item"> 
            <div class="">
                <textarea name="question" placeholder="遗留问题" class="layui-textarea"></textarea>
            </div>
        </div> 
        <div class="layui-form-item"> 
            <label class="layui-form-label">沟通时间</label>
            <div class="layui-input-inline">     
                <input class="layui-input formdate" lay-verify="required" name="record_at" placeholder="" >
            </div>
        </div>

        <div class="layui-form-item  editevent">
            <div class="layui-input-block">
                {{ csrf_field() }}
                <button class="layui-btn" id="recordsubform" lay-submit="" lay-filter="recordsubform">提交记录</button>
            </div>
        </div>
    </form>
</div>