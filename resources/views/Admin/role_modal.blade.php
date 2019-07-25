<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">添加角色</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert" id="error_message"></div>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">角色名字</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_role_name" placeholder="角色名字">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">角色标签</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_role_tab" placeholder="角色标签">
                        </div><span style="color: red">* 该字段极为重要不可随意修改 要修改请找技术人员</span>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">角色状态</label>
                        @foreach($role_status as $k=>$v)
                            <div class="col-sm-2">
                                <p class="form-control-static">
                                    <input type="radio" class="modal_role_status" name='modal_role_status' value="{{$k}}" @if($k==1) checked @endif> {{$v}}
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