<?php

//项目流程

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SecondController;
use App\Model\Projects as ProjectsModel;
use App\Model\Customer as CustomerModel;
use App\Model\Record as RecordModel;
use App\Model\Contract as ContractModel;
use App\Model\Process as ProcessModel;


class ProcessController extends SecondController {

    public $returnMsg = ['code' => 200, 'data' => [], 'msg' => ''];

    public function __construct() {
        parent::__construct();
    }

    //项目下单表列表
    public function lists(Request $request){
        
        $data['customer'] = Db::table('customer')->whereNull('deleted_at')->orderBy('id', 'desc')->pluck('username', 'id')->toArray();
        $data['adminer'] = Db::table('admin_role')->leftJoin('admin', 'admin_role.admin_id', '=', 'admin.id')
                        ->where([['admin.status', 1]])->pluck('admin.name', 'admin.id')->toArray();
        $data['project'] = Db::table('project')->whereBetween('status', [1, 10])->whereNull('deleted_at')->orderBy('id', 'desc')->pluck('name', 'id')->toArray();
        //已签合同的项目
        $data['contract_project'] = Db::table('contract')->leftjoin('project', 'project.id', '=', 'contract.project_id')->whereNull('contract.deleted_at')->orderBy('contract.id', 'desc')->pluck('project.name', 'project.id')->toArray();
        return view('manage.lists',['title' => '项目下单表', 'data' => json_encode($data) ]);
    }
    
    //待处理列表
    public function todolist(Request $request){
        
        $data['customer'] = Db::table('customer')->whereNull('deleted_at')->orderBy('id', 'desc')->pluck('username', 'id')->toArray();
        $data['adminer'] = Db::table('admin_role')->leftJoin('admin', 'admin_role.admin_id', '=', 'admin.id')
                        ->where([['admin.status', 1]])->pluck('admin.name', 'admin.id')->toArray();
        $data['project'] = Db::table('project')->whereBetween('status', [1, 10])->whereNull('deleted_at')->orderBy('id', 'desc')->pluck('name', 'id')->toArray();
        return view('manage.todolist',['title' => '待处理项目', 'data' => json_encode($data) ]);
    }
    
    //已处理列表
    public function handled(Request $request){
        $data['customer'] = Db::table('customer')->whereNull('deleted_at')->orderBy('id', 'desc')->pluck('username', 'id')->toArray();
        $data['adminer'] = Db::table('admin_role')->leftJoin('admin', 'admin_role.admin_id', '=', 'admin.id')
                        ->where([['admin.status', 1]])->pluck('admin.name', 'admin.id')->toArray();
        $data['project'] = Db::table('project')->whereBetween('status', [1, 10])->whereNull('deleted_at')->orderBy('id', 'desc')->pluck('name', 'id')->toArray();
        return view('manage.todolist',['title' => '已处理项目', 'data' => json_encode($data) ]);
    }


    
    public function addprocess(Request $request) {
        if (!$request->isMethod('post')) {
            $this->returnMsg['msg'] = '请求方式有误!';
        }
        
        if (!$request->filled('project_id') || !$request->filled('salesman_id') || !$request->filled('admin_id') || !$request->filled('technical_id') || !$request->filled('customer_id') || !$request->filled('develop_id') || !$request->filled('deliver_date')) {
            $this->returnMsg['msg'] = '提交数据有误!';
        }

        if ($this->returnMsg['msg'] != '') {
            $this->returnMsg['code'] = 304;
            return json_encode($this->returnMsg);
        }

        $savedata = [];
        $savedata['project_id'] = $request->project_id;
        $savedata['project_name'] = ProjectsModel::where('id', $request->project_id)->value('name');
        $savedata['salesman_id'] = $request->salesman_id;
        $savedata['admin_id'] = $request->admin_id;
        $savedata['technical_id'] = $request->technical_id;
        $savedata['customer_id'] = $request->customer_id;
        $savedata['customer_str'] = $request->customer_str;
        $savedata['company_str'] = $request->company_str;
        $savedata['note'] = $request->note;
        $savedata['status'] = 0;
        $savedata['develop_id'] = $request->develop_id;
        $savedata['develop_date'] = $request->develop_date;
        $savedata['deliver_date'] = $request->deliver_date;
       
        $this->returnMsg['data'] = $savedata;
        
        if ($request->filled('editid')) {
            $savedata['updated_at'] = date('Y-m-d H:i:s');
            $msg = ProcessModel::where('id', $request->editid)->update($savedata);
        } else {
            $savedata['created_at'] = date('Y-m-d H:i:s');
            $msg = ProcessModel::insert($savedata);
        }

        if ($msg == true) {
            $this->returnMsg['msg'] = '成功!';
        } else {
            $this->returnMsg['code'] = 500;
            $this->returnMsg['msg'] = '失败!';
        }

        return json_encode($this->returnMsg);
    }

    public function getrecord(Request $request) {
        if (!$request->filled('record_id')) {
            $this->returnMsg['msg'] = '提交参数有误!';
            $this->returnMsg['code'] = 304;
            return json_encode($this->returnMsg);
        }

        $infos = RecordModel::where('id', $request->record_id)->first();

        $this->returnMsg['data'] = $infos;
        $this->returnMsg['msg'] = 'success';
        return json_encode($this->returnMsg);
    }

    public function getrecordlist(Request $request) {
//         DB::connection()->enableQueryLog();
        $page = $request->page <= 1 ? 0 : $request->page - 1;
        $limit = $request->filled('limit') ? $request->limit : 10;
        $where = [];
        $request->filled('input_id') && $where[] = ['records.input_id', $request->input_id];
        $request->filled('customer_id') && $where[] = ['records.customer_id', $request->customer_id];
        $request->filled('project_id') && $where[] = ['records.project_id', $request->project_id];

        $total = RecordModel::where($where);
        $lists = RecordModel::selectRaw('records.*')->where($where);

        if ($request->filled('record_at')) {
            $ctimearr = explode('@', $request->record_at);
            $lists = $lists->whereBetween('records.record_at', [strtotime($ctimearr[0]), strtotime($ctimearr[1])]);
            $total = $total->whereBetween('records.record_at', [strtotime($ctimearr[0]), strtotime($ctimearr[1])]);
        }
        
        // 非超级管理员 只能查看属于自己的数据
        if ($this->arr_login_user['is_super'] != 1) {
            $total = $total->where('records.input_id', $this->arr_login_user['id']);
            $list = $list->where('records.input_id', $this->arr_login_user['id']);
        }

        if ($request->actiontype == 'notlist') {
            $lists = $lists->orderBy('records.id', 'desc')->get();
        } else {
            $total = $total->count();
            $lists = $lists->orderBy('records.id', 'desc')->offset($page * $limit)->take($limit)->get();
            $this->returnMsg['total'] = $total;
        }
//       dump(DB::getQueryLog());  
        $this->returnMsg['data'] = $lists;
        $this->returnMsg['msg'] = 'success';
        return json_encode($this->returnMsg);
    }

    public function delrecord(Request $request) {
        if (!$request->isMethod('delete')) {
            $this->returnMsg['code'] = 304;
            $this->returnMsg['msg'] = '请求方式有误!';
            return $this->returnMsg;
        }
        if (!$request->filled('record_id')) {
            $this->returnMsg['code'] = 304;
            $this->returnMsg['msg'] = '提交参数有误!';
            return $this->returnMsg;
        }

        $info = RecordModel::where('id', $request->record_id)->delete();
        if (!$info) {
            $this->returnMsg['code'] = 304;
            $this->returnMsg['msg'] = '删除失败!';
        }

        return $this->returnMsg;
    }
    
    
    //添加项目下单表,获取项目部分信息
    public function getproject(Request $request){
        if (!$request->filled('project_id')) {
            $this->returnMsg['code'] = 304;
            $this->returnMsg['msg'] = '提交参数有误!';
            return $this->returnMsg;
        }
        
        $infos = ContractModel::where('contract.project_id', $request->project_id)
                ->leftjoin('project', 'project.id', '=', 'contract.project_id')
                ->leftjoin('customer', 'customer.id', '=', 'project.customer_id')
                ->select('project.id', 'project.name', 'project.admin_id', 'project.customer_id', 'project.customer_name', 'project.note', 'customer.company', 'customer.phone', 'customer.landline', 'contract.contract_time', 'contract.end_time')->first();

        if($infos){
           $this->returnMsg['data'] = $infos; 
           $this->returnMsg['msg'] = 'success';
        }else{
            $this->returnMsg['code'] = 404;
            $this->returnMsg['data'] = '';
            $this->returnMsg['msg'] = '未找到相应数据';
        }
        return json_encode($this->returnMsg);
    }
    

}
