<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">添加合同</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert" id="error_message" ></div>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">项目名字</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_project_name" placeholder="项目名字">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">合同标题</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_contract_title" placeholder="合同标题">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">合同金额</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_contract_money" placeholder="合同金额 单位:元">
                        </div>单位:元
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">合同描述</label>
                        <div class="col-sm-5">
                            <textarea class="form-control" rows="5" id="modal_contract_describe" placeholder="合同描述"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">签约时间</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control datetimepicker" placeholder="请选择合同签约时间" id="modal_contract_time" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">生效时间</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control datetimepicker" placeholder="请选择合同生效时间" id="modal_contract_take_effect_time" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">截止时间</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control datetimepicker" placeholder="请选择合同截止时间" id="modal_contract_end_time" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">合同附件</label>
                        <div class="col-sm-5">
                            <button type="button" class="btn btn-primary btn-sm" id="upload_button">点击上传附件</button><span id="show_pic_url"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">合同状态</label>
                        <div class="col-sm-2">
                            <p class="form-control-static">
                                <input type="radio" class="contract_status" name='contract_status' value="1" checked> 生效
                            </p>
                        </div>
                        <div class="col-sm-3">
                            <p class="form-control-static">
                                <input type="radio" class="contract_status" name='contract_status' value="0"> 无效
                            </p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id='edit_project'>提交</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" class="form-control" id="modal_contract_upload">
<form id="ajax_upload_files" enctype="multipart/form-data"><input type="file" id="hide_upload_files" name="ajax_upload_files"></form>