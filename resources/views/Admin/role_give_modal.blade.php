<!-- Modal -->
<div class="modal fade" id="myModalGive" tabindex="-1" role="dialog" aria-labelledby="myModalLabelgive">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabelgive">分配权限</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                   @foreach($arr_power as $v)
                    <div class="checkbox">
                        @for($i=0;$i<=$v['deep']*3;$i++)
                             &nbsp;
                        @endfor
                        <label>
                            <input type="checkbox" value="{{$v['id']}}" class="select_power">
                            {{$v['name']}}
                        </label>
                    </div>
                    @endforeach
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id='give_power'>提交</button>
            </div>
        </div>
    </div>
</div>