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
use App\Model\ProcessNote as ProcessNoteModel;
use App\Model\ProcessAssess as ProcessAssessModel;


class ProcessController extends SecondController {

    public $returnMsg = ['code' => 200, 'data' => [], 'msg' => ''];

    public function __construct() {
        parent::__construct();
    }

    //项目下单表列表
    public function lists(Request $request){
        $data['status'] = config('manage.process_status');
        $data['customer'] = Db::table('customer')->whereNull('deleted_at')->orderBy('id', 'desc')->pluck('username', 'id')->toArray();
        $data['adminer'] = Db::table('admin_role')->leftJoin('admin', 'admin_role.admin_id', '=', 'admin.id')
                        ->where([['admin.status', 1]])->pluck('admin.name', 'admin.id')->toArray();
        //已签合同的项目
        $data['contract_project'] = Db::table('contract')->leftjoin('project', 'project.id', '=', 'contract.project_id')->whereNull('contract.deleted_at')->whereNull('project.deleted_at')->orderBy('contract.id', 'desc')->pluck('project.name', 'project.id')->toArray();
        return view('manage.lists',['title' => '项目下单表', 'data' => json_encode($data) ]);
    }
    
    //项目下单表列表 数据
    public function getlists(Request $request) {
        $page = $request->page <= 1 ? 0 : $request->page - 1;
        $limit = $request->filled('limit') ? $request->limit : 10;
        $where = [];
        $request->filled('project_id') && $where[] = ['process.project_id', $request->project_id];
        $request->filled('salesman_id') && $where[] = ['process.salesman_id', $request->salesman_id];
        $request->filled('status') && $where[] = ['process.status', $request->status];
        
        // 非超级管理员 只能查看属于自己的数据
        if ($this->arr_login_user['is_super'] != 1) {
            $where[] = ['process.salesman_id', $this->arr_login_user['id']];
        }

        $querySql = ProcessModel::where($where)->selectRaw('process.*, admin.name')->leftJoin('admin', 'admin.id', '=', 'process.develop_id')->orderBy('process.id', 'desc');

        if ($request->filled('develop_date')) {
            $ctimearr = explode('@', $request->develop_date);
            $querySql = $querySql->whereBetween('process.develop_date', [$ctimearr[0], $ctimearr[1]]);
        }
        
        if ($request->actiontype == 'notlist') {
            $lists = $querySql->get();
        } else {
            $lists = $querySql->offset($page * $limit)->take($limit)->get();
            $this->returnMsg['total'] = $querySql->count();
        }
        $this->returnMsg['data'] = $lists->toArray();
        $this->returnMsg['msg'] = 'success';
        return json_encode($this->returnMsg);
    }
    
    
    //待处理列表
    public function todolist(Request $request){
        $data['status'] = config('manage.process_status');
        $data['customer'] = Db::table('customer')->whereNull('deleted_at')->orderBy('id', 'desc')->pluck('username', 'id')->toArray();
        $data['adminer'] = Db::table('admin_role')->leftJoin('admin', 'admin_role.admin_id', '=', 'admin.id')
                        ->where([['admin.status', 1]])->pluck('admin.name', 'admin.id')->toArray();
        $data['contract_project'] = Db::table('contract')->leftjoin('project', 'project.id', '=', 'contract.project_id')->whereNull('contract.deleted_at')->whereNull('project.deleted_at')->orderBy('contract.id', 'desc')->pluck('project.name', 'project.id')->toArray();
        return view('manage.todolist',['title' => '待处理项目', 'data' => json_encode($data) ]);
    }
    
    //待处理 项目下单表列表 数据
    public function gettodolist(Request $request) {
        $page = $request->page <= 1 ? 0 : $request->page - 1;
        $limit = $request->filled('limit') ? $request->limit : 10;
        $where = [];
        $request->filled('salesman_id') && $where[] = ['process.salesman_id', $request->salesman_id];
        $request->filled('status') && $where[] = ['process.status', $request->status];
        
        // 待处理列表 查属于当前用户的开发进度的表单
        $where[] = ['process.develop_id', $this->arr_login_user['id']];
        $querySql = ProcessModel::where($where);

        if ($request->filled('develop_date')) {
            $ctimearr = explode('@', $request->develop_date);
            $querySql = $querySql->whereBetween('process.develop_date', [$ctimearr[0], $ctimearr[1]]);
        }
        
        $lists = $querySql->selectRaw('process.*, admin.name')->leftJoin('admin', 'admin.id', '=', 'process.develop_id')->orderBy('process.id', 'desc')->offset($page * $limit)->take($limit)->get();
         
        $this->returnMsg['total'] = $querySql->count();       
        $this->returnMsg['data'] = $lists->toArray();
        $this->returnMsg['msg'] = 'success';
        return json_encode($this->returnMsg);
    }
    
    //已处理列表
    public function handled(Request $request){
        $data['status'] = config('manage.process_status');
        $data['customer'] = Db::table('customer')->whereNull('deleted_at')->orderBy('id', 'desc')->pluck('username', 'id')->toArray();
        $data['adminer'] = Db::table('admin_role')->leftJoin('admin', 'admin_role.admin_id', '=', 'admin.id')
                        ->where([['admin.status', 1]])->pluck('admin.name', 'admin.id')->toArray();
        $data['contract_project'] = Db::table('contract')->leftjoin('project', 'project.id', '=', 'contract.project_id')->whereNull('contract.deleted_at')->whereNull('project.deleted_at')->orderBy('contract.id', 'desc')->pluck('project.name', 'project.id')->toArray();
        $data['process'] = Db::table('process')->whereNull('process.deleted_at')->orderBy('process.id', 'desc')->pluck('process.project_name', 'process.id')->toArray();
        return view('manage.handled',['title' => '已处理开发记录', 'data' => json_encode($data) ]);
    }

    //已处理 项目下单表列表 数据
    public function handledlist(Request $request) {
        $page = $request->page <= 1 ? 0 : $request->page - 1;
        $limit = $request->filled('limit') ? $request->limit : 10;
        $where = [];
        $request->filled('process_id') && $where[] = ['process.id', $request->process_id];
        $request->filled('status') && $where[] = ['process.status', $request->status];
        
        $where[] = ['process_note.admin_id', $this->arr_login_user['id']];
        $querySql = ProcessNoteModel::leftJoin('process', 'process_note.process_id', '=', 'process.id')->where($where); 
        if ($request->filled('develop_date')) {
            $ctimearr = explode('@', $request->develop_date);
            $querySql = $querySql->whereBetween('process.develop_date', [$ctimearr[0], $ctimearr[1]]);
        }
        
        $lists = $querySql->selectRaw('process.id, process.project_name, process.status, process.technical_str, process.admin_str, process_note.*, admin.name')->leftJoin('admin', 'admin.id', '=', 'process.develop_id')->orderBy('process_note.id', 'desc')->offset($page * $limit)->take($limit)->get();
        
        $this->returnMsg['total'] = $querySql->count();        
        $this->returnMsg['data'] = $lists->toArray();
        $this->returnMsg['msg'] = 'success';
        return json_encode($this->returnMsg);
    }
    

    //添加下单表
    public function addprocess(Request $request) {
        if (!$request->filled('project_id') || !$request->filled('salesman_id') || !$request->filled('admin_id') || !$request->filled('technical_id') || !$request->filled('develop_id') || !$request->filled('develop_date') || !$request->filled('deliver_date')) {
            $this->returnMsg['msg'] = '提交数据有误!';
        }

        if ($this->returnMsg['msg'] != '') {
            $this->returnMsg['code'] = 304;
            return json_encode($this->returnMsg);
        }
//      DB::connection()->enableQueryLog();  
        $savedata = [];
        $savedata['project_id'] = $request->project_id;
        $savedata['project_name'] = ProjectsModel::where('id', $request->project_id)->value('name');
        $savedata['salesman_id'] = $request->salesman_id;
        $savedata['salesman_str'] = $request->salesman_str;
        $savedata['admin_id'] = $request->admin_id;
        $savedata['admin_str'] = $request->admin_str;
        $savedata['technical_id'] = $request->technical_id;
        $savedata['technical_str'] = $request->technical_str;
        $savedata['customer_str'] = $request->customer_str;
        $savedata['company_str'] = $request->company_str;
        $savedata['note'] = $request->note;
        $savedata['status'] = $request->status;
        $savedata['develop_id'] = $request->develop_id;
        $savedata['develop_date'] = $request->develop_date;
        $savedata['deliver_date'] = $request->deliver_date;
//        dump(DB::getQueryLog()); 
        
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

    public function detail(Request $request) {
        if (!$request->filled('eid')) {
            $this->returnMsg['msg'] = '提交参数有误!';
            $this->returnMsg['code'] = 304;
            return json_encode($this->returnMsg);
        }

//DB::connection()->enableQueryLog();
        $infos = ProcessModel::where('id', $request->eid)->first();
//        return json_encode(DB::getQueryLog());

        $this->returnMsg['data'] = $infos;
        $this->returnMsg['msg'] = 'success';
        return json_encode($this->returnMsg);
    }


    public function del(Request $request) {
        if (!$request->filled('del_id')) {
            $this->returnMsg['code'] = 304;
            $this->returnMsg['msg'] = '提交参数有误!';
            return $this->returnMsg;
        }

        $info = ProcessModel::where('id', $request->del_id)->delete();
        if (!$info) {
            $this->returnMsg['code'] = 304;
            $this->returnMsg['msg'] = '删除失败!';
        }

        return $this->returnMsg;
    }
  
    
    public function edit(Request $request) {
        if (!$request->filled('eid')) {
            $this->returnMsg['code'] = 304;
            $this->returnMsg['msg'] = '提交参数有误!';
            return $this->returnMsg;
        }

        $infos = ProcessModel::where('id', $request->eid)->first();
        if (!$infos) {
            $this->returnMsg['code'] = 304;
            $this->returnMsg['msg'] = '修改失败!';
        }

        $this->returnMsg['data'] = $infos;
        $this->returnMsg['msg'] = 'success';
        return $this->returnMsg;
    }
    
    
    //添加下单表
    public function addnote(Request $request) {
        if (!$request->filled('process_id') || !$request->filled('develop_id') || !$request->filled('end_date') || !$request->filled('over_date') || !$request->filled('status') || !$request->filled('remarks') || !$request->filled('note')) {
            $this->returnMsg['msg'] = '提交数据有空!';
        }

        if ($this->returnMsg['msg'] != '') {
            $this->returnMsg['code'] = 304;
            return json_encode($this->returnMsg);
        }
        
//      DB::connection()->enableQueryLog();  
        $savedata = [];
        $savedata['process_id'] = $request->process_id;
        $savedata['admin_id'] = $this->arr_login_user['id'];
        $savedata['admin_name'] = $this->arr_login_user['name'];
        $savedata['over_date'] = $request->over_date;
        $savedata['end_date'] = $request->end_date;
        $savedata['remarks'] = $request->remarks;
        $savedata['note'] = $request->note;
        $savedata['type'] = 1;
        $savedata['step'] = $request->status;
        $savedata['develop_id'] = $request->develop_id;
        
        
        if ($request->filled('editid')) {
            $savedata['updated_at'] = date('Y-m-d H:i:s');
            $msg = ProcessNoteModel::where('id', $request->editid)->update($savedata);
        } else {
            $savedata['created_at'] = date('Y-m-d H:i:s');
            $msg = DB::transaction(function()use($savedata, $request){
                ProcessNoteModel::insert($savedata);
                $updatas = [
                'status' => $request->status,
                'develop_id' => $request->develop_id,
                ];
               return ProcessModel::where([
                   ['id', $request->process_id],
                   ['status', '<=', $request->status]
                   ])->update($updatas);
            }, 5);
        }
//        dump(DB::getQueryLog()); 
        
        if ($msg == true) {
            $this->returnMsg['msg'] = '成功!';
        } else {
            $this->returnMsg['code'] = 500;
            $this->returnMsg['msg'] = '失败!';
        }

        return json_encode($this->returnMsg);
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
