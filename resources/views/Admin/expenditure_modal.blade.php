<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">添加支出记录</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert" id="error_message" ></div>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">用途</label>
                        <div class="col-sm-5">
                            <select class="form-control" id="modal_expenditure_name">
                                @foreach($arr_expenditure_name as $k=>$v)
                                    <option value="{{$k}}">{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">经办人</label>
                        <div class="col-sm-5">
                            <select class="form-control" id="modal_consumer">
                                @foreach($arr_admin_list as $k=>$v)
                                    <option value="{{$v->id}}">{{$v->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">支出金额</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_expenditure_money" placeholder="支出金额">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">支付方式</label>
                        <div class="col-sm-5">
                            <select class="form-control" id="modal_expenditure_method">
                                @foreach($pay_method as $k=>$v)
                                    <option value="{{$k}}">{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">付款状态</label>
                        <div class="col-sm-2">
                            <p class="form-control-static">
                                <input type="radio" class="modal_expenditure_status" name='modal_expenditure_status' value="0" checked> 未付款
                            </p>
                        </div>
                        <div class="col-sm-3">
                            <p class="form-control-static">
                                <input type="radio" class="modal_expenditure_status" name='modal_expenditure_status' value="1"> 已付款
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">支出备注</label>
                        <div class="col-sm-5">
                            <textarea class="form-control" rows="5" id="modal_expenditure_remarks" placeholder="支出备注"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">支付凭证</label>
                        <div class="col-sm-5">
                            <button type="button" class="btn btn-primary btn-sm" id="upload_button">点击上传凭证</button><span id="show_pic_url"></span>
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

<input type="text" class="form-control" id="modal_expenditure_upload" style="display: none">
<form id="ajax_upload_files" enctype="multipart/form-data"><input type="file" id="hide_upload_files" name="ajax_upload_files"></form>