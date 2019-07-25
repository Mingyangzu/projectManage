<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">添加导航分类</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert" id="error_message"></div>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">导航分类名字</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_menu_cate_name" placeholder="导航分类名字">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">导航分类描述</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_menu_cate_describe" placeholder="导航分类描述">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">导航分类状态</label>
                        @foreach($menu_status as $k=>$v)
                            <div class="col-sm-2">
                                <p class="form-control-static">
                                    <input type="radio" class="modal_menu_cate_status" name='modal_menu_cate_status' value="{{$k}}" @if($k==1) checked @endif> {{$v}}
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