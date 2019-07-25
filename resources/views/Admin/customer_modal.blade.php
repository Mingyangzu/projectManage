<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">修改客户信息</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert" id="error_message" ></div>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">公司名称</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="1" id="modal_company_names" placeholder="请输入公司名称"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">客户名字</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_customer_names" placeholder="请输入客户名字">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">客户类型</label>
                        <div class="col-sm-2">
                            <p class="form-control-static">
                                <input type="radio" class="modal_customer_type" name='modal_customer_type' value="0"> 个人
                            </p>
                        </div>
                        <div class="col-sm-2">
                            <p class="form-control-static">
                                <input type="radio" class="modal_customer_type" name='modal_customer_type' value="1" checked> 企业
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">客户手机</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_customer_tel" placeholder="请输入客户手机">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">座机</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_customer_landline" placeholder="请输入座机">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">微信号</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_customer_wechat" placeholder="请输入微信号">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">职位</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_customer_position" placeholder="请输入客户职位">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">年营业额</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_customer_turnover" placeholder="请输入年营业额(单位:万元)">
                        </div>单位:万元
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">地址</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="1" id="modal_customer_address" placeholder="请输入公司地址"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">所在行业</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_customer_industry" placeholder="请输入公司所在行业">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">主营业务</label>
                        <div class="col-sm-5">
                            <textarea class="form-control" rows="5" id="modal_customer_main_business" placeholder="请输入公司主营业务"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">公司规模</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="modal_customer_scale" placeholder="请输入公司规模(单位:人数)">
                        </div>单位:人数
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">客户来源</label>
                        <div class="col-sm-5">
                            <select class="form-control" id="modal_customer_source">
                                @foreach($customer_source as $k=>$v)
                                    <option value="{{$k}}">{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">客户备注</label>
                        <div class="col-sm-5">
                            <textarea class="form-control" rows="5" id="modal_customer_remarks" placeholder="请输入客户备注"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">客户状态</label>
                        @foreach($customer_status as $k=>$v)
                            <div class="col-sm-2">
                                <p class="form-control-static">
                                    <input type="radio" class="modal_customer_status" name='modal_customer_status' value="{{$k}}" @if($k==1) checked @endif> {{$v}}
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