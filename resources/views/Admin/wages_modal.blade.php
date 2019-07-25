<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">修改薪资信息</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert" id="error_message" ></div>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">员工名字</label>
                        <div class="col-sm-5">
                            <select class="form-control" id="modal_wages_user_name">
                                @foreach($admin_list as $v)
                                    <option value="{{$v->id}}">{{$v->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">薪资时间</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control datetimepicker" placeholder="请选择薪资时间"  readonly id="modal_wages_time">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">总薪资</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_wages_total" placeholder="请输入总薪资">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">基本薪资</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_wages_basic" placeholder="请输入基本薪资">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">岗位薪资</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_wages_post" placeholder="请输入岗位薪资">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">全勤</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_wages_full" placeholder="请输入全勤奖">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">学历</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_wages_education" placeholder="请输入学历奖">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">年限薪资</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_wages_years" placeholder="请输入年限薪资">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">经营津贴</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_wages_oper_allowance" placeholder="请输入经营津贴">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">技能薪资</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_wages_skill" placeholder="请输入技能薪资">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">社保扣除</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_wages_social_security" placeholder="请输入社保扣除">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">应发绩效</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_wages_payable_merit" placeholder="请输入应发绩效">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">绩效扣除</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_wages_deduct_merit" placeholder="请输入扣除的绩效">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">提成</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_wages_royalty" placeholder="请输入提成">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">请假</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_wages_leave" placeholder="请输入请假">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">迟到</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_wages_late" placeholder="请输入迟到">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">特殊津贴</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_wages_perquisites" placeholder="请输入特殊津贴">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">奖金</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_wages_bonus" placeholder="请输入奖金">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">薪资备注</label>
                        <div class="col-sm-5">
                            <textarea class="form-control" rows="5" id="modal_wages_remarks" placeholder="请输入薪资备注"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">薪资状态</label>
                        @foreach($wages_status as $k=>$v)
                            <div class="col-sm-2">
                                <p class="form-control-static">
                                    <input type="radio" class="modal_wages_status" name='modal_wages_status' value="{{$k}}" @if($k==1) checked @endif> {{$v}}
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