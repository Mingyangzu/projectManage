<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">添加权限</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert" id="error_message"></div>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">权限名字</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_power_name" placeholder="权限名字">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">权限链接</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_power_link" placeholder="权限链接">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">权限状态</label>
                        @foreach($power_status as $k=>$v)
                            <div class="col-sm-2">
                                <p class="form-control-static">
                                    <input type="radio" class="modal_power_status" name='modal_power_status' value="{{$k}}" @if($k==1) checked @endif> {{$v}}
                                </p>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">父权限</label>
                        <div class="col-sm-5">
                            <select class="form-control" id="modal_power_parent">
                                <option value="0">一级权限</option>
                                @foreach($arr_power_list as $v)
                                    <option value="{{$v['id']}}">{{$v['name']}}</option>
                                @endforeach
                            </select>
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