<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">添加管理员</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert" id="error_message"></div>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">管理员名字</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_admin_name" placeholder="管理员名字">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">邮箱</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_admin_email" placeholder="邮箱">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">手机</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_admin_phone" placeholder="手机">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">身份证</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_admin_id_card" placeholder="身份证">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">银行卡号</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_admin_bank_card" placeholder="银行卡号">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">状态</label>
                        @foreach($admin_status as $k=>$v)
                            <div class="col-sm-2">
                                <p class="form-control-static">
                                    <input type="radio" class="modal_admin_status" name='modal_admin_status' value="{{$k}}" @if($k==1) checked @endif> {{$v}}
                                </p>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">角色</label>
                        <div class="col-sm-5">
                            <select multiple class="form-control" id="modal_admin_role">
                                @foreach($obj_role as $v)
                                    <option value="{{$v->id}}">{{$v->name}}</option>
                                @endforeach
                            </select>
                        </div>角色可多选
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">性别</label>
                        @foreach($admin_sex as $k=>$v)
                            <div class="col-sm-2">
                                <p class="form-control-static">
                                    <input type="radio" class="modal_admin_sex" name='modal_admin_sex' value="{{$k}}" @if($k==0) checked @endif> {{$v}}
                                </p>
                            </div>
                        @endforeach
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