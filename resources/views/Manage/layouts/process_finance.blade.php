<div id='financebox' style='display: none; margin: 10px 20px;'>
    <form class="layui-form  layui-form-pane" lay-filter="financeform" onsubmit="return false;">
        <input type="hidden" name="process_id" lay-filter="process_id">
        <div class="layui-form-item">
            <label class="layui-form-label"> 项目 </label>
            <div class="layui-input-inline"  style="width: 300px;">
                <input class="layui-input" name="process_name" readonly="true" lay-filter="process_name">
            </div>
        </div>   

        <div class="layui-form-item detailevent">
            <label class="layui-form-label" style="width: 110px;"> <em style='color:red;'>*</em> 收款阶段 </label>
            <div class="layui-input-inline">
                <select name="status" class="layui-input" lay-verify="required" lay-filter="status">
                    <option value=""></option>
                    @foreach (json_decode($data)->develop_status as $k => $v)
                    @if($k < 10)
                    <option value="{{$k}}">{{$v}}</option>
                    @endif
                    @endforeach
                </select>
            </div>
        </div> 

        <div class="layui-form-item"> 
            <div class=""> <span class="notetitle">收款备注</span>
                <textarea name="note" placeholder="" lay-filter="note" class="layui-textarea" ></textarea>
            </div>
        </div>

        <div class="layui-form-item  editevent">
            <div class="layui-input-block">
                {{ csrf_field() }}
                <button class="layui-btn" id="subfinance" lay-submit="" lay-filter="subfinance">确认收款</button>
            </div>
        </div>
    </form>
</div>

<script>
    layui.use(['form', 'jquery'], function () {
        var $ = layui.jquery, form = layui.form;
        form.on('submit(subfinance)', function (data) {
            $('#subfinance').addClass('layui-btn-disabled');
            $('#subfinance').attr('disabled', 'disabled');
            $.ajax({
                url: '/process/subfinance',
                data: data.field,
                type: "post",
                dataType: 'json',
                success: function (res) {
                    if (res.code == 200) {
                        layer.msg("确认收款成功", {
                            icon: 1
                        });
                        form.val('financeform', {
                            process_id: ''
                            , process_name: ''
                            , status: ''
                            , note: ''
                        });
                        layer.closeAll('page');
                    } else {
                        layer.msg(res.msg, {
                            icon: 5
                        });
                    }
                },
                complete: function (res) {
                    $('#subfinance').removeClass('layui-btn-disabled');
                    $('#subfinance').removeAttr('disabled');
                }
            });
            return false;
        });

    });

</script>

