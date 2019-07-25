<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">修改项目</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert" id="error_message" ></div>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">客户电话</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_customer_company" placeholder="客户手机或座机">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">项目名字</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_project_name" placeholder="项目名字">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">项目周期</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_project_cycle" placeholder="项目周期(单位:天)">
                        </div>单位:天
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">项目备注</label>
                        <div class="col-sm-5">
                            <textarea class="form-control" rows="5" id="modal_project_remarks" placeholder="项目备注"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">项目进度</label>
                        <div class="col-sm-5">
                            <select class="form-control" id="modal_project_step">
                                @foreach($arr_step as $v)
                                    <option value="{{$v->id}}">{{$v->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">项目经理</label>
                        <div class="col-sm-5">
                            <select class="form-control" id="modal_project_admin">
                                @foreach($arr_project_admin as $k=>$v)
                                    <option value="{{$v->id}}">{{$v->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">项目类型</label>
                        <div class="col-sm-5">
                            <select class="form-control" id="modal_project_type">
                                @foreach($arr_type as $v)
                                    <option value="{{$v->id}}">{{$v->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">项目状态</label>
                        <div class="col-sm-2">
                            <p class="form-control-static">
                                <input type="radio" class="modal_project_status" name='project_status' value="1" checked> 有效
                            </p>
                        </div>
                        <div class="col-sm-2">
                            <p class="form-control-static">
                                <input type="radio" class="modal_project_status" name='project_status' value="0"> 无效
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">是否投标项目</label>
                        <div class="col-sm-2">
                            <p class="form-control-static">
                                <input type="radio" class="modal_project_bid" name='project_bid' value="0" checked> 否
                            </p>
                        </div>
                        <div class="col-sm-2">
                            <p class="form-control-static">
                                <input type="radio" class="modal_project_bid" name='project_bid' value="1"> 是
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">收款状态</label>
                        <div class="col-sm-2">
                            <p class="form-control-static">
                                <input type="radio" class="modal_project_pay_status" name='project_pay_status' value="0" checked> 收款阶段
                            </p>
                        </div>
                        <div class="col-sm-2">
                            <p class="form-control-static">
                                <input type="radio" class="modal_project_pay_status" name='project_pay_status' value="1"> 已收款
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