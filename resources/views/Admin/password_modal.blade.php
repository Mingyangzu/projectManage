<!-- Modal -->
<div class="modal fade" id="m_p_Modal" tabindex="-1" role="dialog" aria-labelledby="m_p_ModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="m_p_ModalLabel">修改密码</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert" id="error_pwd_message" ></div>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">新密码</label>
                        <div class="col-sm-5">
                            <input type="password" class="form-control" id="modal_new_password" placeholder="请输入新密码">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">确认密码</label>
                        <div class="col-sm-5">
                            <input type="password" class="form-control" id="modal_new_password_ok" placeholder="请再次输入新密码">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id='submit_modify_password' data-admin_id="{{$arr_login_admin['id']}}">提交</button>
            </div>
        </div>
    </div>
</div>