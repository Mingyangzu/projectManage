<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">添加财务记录</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert" id="error_message" ></div>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">合同名字</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_contract_name" placeholder="合同名字">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">本次收款摘要</label>
                        <div class="col-sm-5">
                            <select class="form-control" id="modal_collection_name">
                                @foreach($connection_records_name as $k=>$v)
                                    <option value="{{$k}}">{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">本次收款金额</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_collection_money" placeholder="本次收款金额 单位:元">
                        </div>单位：元
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">本次收款方式</label>
                        <div class="col-sm-5">
                            <select class="form-control" id="modal_pay_method">
                                @foreach($pay_method as $k=>$v)
                                    <option value="{{$k}}">{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">本次收款状态</label>
                        <div class="col-sm-2">
                            <p class="form-control-static">
                                <input type="radio" class="project_collection_status" name='project_collection_status' value="0" checked> 未收款
                            </p>
                        </div>
                        <div class="col-sm-3">
                            <p class="form-control-static">
                                <input type="radio" class="project_collection_status" name='project_collection_status' value="1"> 已收款
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