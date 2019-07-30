<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\SecondController;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use App\Rules\verfcount;
use App\Rules\verfmoney;
use Illuminate\Http\Request;
use App\Model\Customer as CustomerModel;
use App\Model\Projects as ProjectsModel;
use App\Model\Contract as ContractModel;

class ProjectController extends SecondController {

    public $returnMsg = ['code' => 200, 'data' => [], 'msg' => ''];

    public function __construct() {
        parent::__construct();
    }

    //项目列表
    public function index() {
        $data['status'] = $this->contract_status;
        $data['type'] = Db::table('type')->where('status', 1)->pluck('name', 'id')->toArray();
        $data['pay_status'] = $this->project_pay_status;
        $data['customer'] = Db::table('customer')->whereNull('deleted_at')->orderBy('id', 'desc')->pluck('username', 'id')->toArray();
        $data['adminer'] = Db::table('admin_role')->leftJoin('admin', 'admin_role.admin_id', '=', 'admin.id')
                        ->where([['admin_role.role_id', 2], ['admin.status', 1]])->pluck('admin.name', 'admin.id')->toArray();
        return view('Manage.project', ['title' => '项目列表', 'data' => json_encode($data)]);
    }

    public function projectlist(Request $request) {
//            DB::connection()->enableQueryLog();
        $page = $request->filled('page') <= 1 ? 0 : $request->page - 1;
        $limit = $request->filled('limit') ? 10 : $request->limit;
        $where = [];
        $request->filled('name') && $where[] = ['project.name', 'like', '%' . $request->name . '%'];
        $request->filled('customer_name') && $where[] = ['project.customer_name', 'like', '%' . $request->customer_name . '%'];
//        $request->filled('phone') && $where[] = ['project.phone', 'like', '%' . $request->phone . '%'];
        $request->filled('admin_name') && $where[] = ['project.admin_name', 'like', '%' . $request->admin_name . '%'];
        $request->filled('type_id') && $where[] = ['project.type_id', $request->type_id];
        $request->filled('status') && $where[] = ['project.status', $request->status];
        $request->filled('payment_status') && $where[] = ['project.payment_status', $request->payment_status];
        
        $total = ProjectsModel::where($where);
        $lists = ProjectsModel::selectRaw('project.*, count(records.project_id) total')
                ->leftJoin('records', 'project.id', '=', 'records.project_id')
                ->where($where);
        $lists = $lists->whereNull('records.deleted_at');

        if ($request->filled('deliver_date')) {
            $ctimearr = explode('@', $request->deliver_date);
            $lists = $lists->whereBetween('project.deliver_date', [strtotime($ctimearr[0]), strtotime($ctimearr[1])]);
            $total = $total->whereBetween('project.deliver_date', [strtotime($ctimearr[0]), strtotime($ctimearr[1])]);
        }

        $total = $total->count();
        $lists = $lists->groupBy('project.id')->orderBy('project.id', 'desc')->skip($page * $limit)->take($limit)->get();
//       dump(DB::getQueryLog());  
        $this->returnMsg['total'] = $total;
        $this->returnMsg['data'] = $lists;
        $this->returnMsg['msg'] = 'success';
        return json_encode($this->returnMsg);
    }

    // 添加客户
    public function addproject(Request $request) {
        if (!$request->isMethod('post')) {
            $this->returnMsg['msg'] = '请求方式有误!';
        }else if (!$request->filled('name')) {
            $this->returnMsg['msg'] = '项目名为必填项!';
        }else if (!$request->filled('customer_id')) {
            $this->returnMsg['msg'] = '客户为必选项!';
        }else if (!$request->filled('type_id')) {
            $this->returnMsg['msg'] = '项目类型必选项!';
        }else if (!$request->filled('status')) {
            $this->returnMsg['msg'] = '项目状态必选项!';
        }else if (!$request->filled('note')) {
            $this->returnMsg['msg'] = '需求说明为必选填项!';
        }
        
        if($this->returnMsg['msg'] != ''){
            $this->returnMsg['code'] = 304;
            return json_encode($this->returnMsg);
        }
        
        
        $savedata = [];
        $savedata['name'] = $request->name;
        $savedata['customer_id'] = $request->customer_id;
        $savedata['customer_name'] = Db::table('customer')->where('id', $request->customer_id)->value('username');
        $savedata['type_id'] = $request->type_id;
        
        $request->filled('status') && $savedata['status'] = $request->status;
        $request->filled('develop_date') && $savedata['develop_date'] = $request->develop_date;
        $request->filled('deliver_date') && $savedata['deliver_date'] = $request->deliver_date;
        $request->filled('payment_status') && $savedata['payment_status'] = $request->payment_status;
        $request->filled('is_bid') && $savedata['is_bid'] = $request->is_bid;
        
        $request->filled('admin_id') && $savedata['admin_id'] = $request->admin_id;
        $request->filled('admin_id') && $savedata['admin_name'] = Db::table('admin')->where('id', $request->admin_id)->value('name');
        
        $savedata['note'] = $request->note;
        $request->filled('remarks') && $savedata['remarks'] = $request->remarks;

        if ($request->filled('editid')) {
            $savedata['last_time'] = time();
            $msg = ProjectsModel::where('id', $request->editid)->update($savedata);
        } else {
            $savedata['input_id'] = $this->arr_login_user['id'];
            $savedata['input_name'] = $this->arr_login_user['name'];
            $savedata['create_time'] = time();
            $msg = ProjectsModel::insert($savedata);
        }
//return json_encode($savedata);
        if ($msg == true) {
            $this->returnMsg['msg'] = '成功!';
        } else {
            $this->returnMsg['code'] = 500;
            $this->returnMsg['msg'] = '失败!';
        }

        return json_encode($this->returnMsg);
    }

    //获取项目信息
    public function getproject(Request $request) {
        if (!$request->filled('project_id')) {
            $this->returnMsg['code'] = 304;
            $this->returnMsg['msg'] = '提交参数有误!';
            return $this->returnMsg;
        }
        
        $infos = ProjectsModel::where('id', $request->project_id)->first();

//            $infos->show_status=$this->customer_status[$infos->status];
//            $infos->show_source=$this->customer_source[$infos->source];
//            $infos->show_type=$this->customer_type[$infos->type];

        if ($infos) {
//            $infos->create_time = date('Y-m-d H:i:s', $infos->create_time);
//            $infos->last_time = date('Y-m-d H:i:s', $infos->last_time);
            $this->returnMsg['msg'] = '成功!';
            $this->returnMsg['data'] = $infos;
        } else {
            $this->returnMsg['code'] = 500;
            $this->returnMsg['msg'] = '获取失败!';
        }

        return json_encode($this->returnMsg);
    }

    //软删除项目
    public function delproject(Request $request) {
        if (!$request->isMethod('delete')) {
            $this->returnMsg['code'] = 304;
            $this->returnMsg['msg'] = '请求方式有误!';
            return $this->returnMsg;
        }
        if (!$request->filled('project_id')) {
            $this->returnMsg['code'] = 304;
            $this->returnMsg['msg'] = '提交参数有误!';
            return $this->returnMsg;
        }

        $info = ProjectsModel::where('id', $request->project_id)->delete();
        if (!$info) {
            $this->returnMsg['code'] = 304;
            $this->returnMsg['msg'] = '删除失败!';
        }

        return $this->returnMsg;
    }

}
