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
        $data['customer'] = Db::table('customer')->orderBy('id', 'desc')->pluck('username', 'id')->toArray();
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
        $lists = ProjectsModel::selectRaw('project.*, count(record.project_id) total')
                ->leftJoin('record', 'project.id', '=', 'record.project_id')
                ->where($where);

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
            $this->returnMsg['code'] = 304;
            $this->returnMsg['msg'] = '请求方式有误!';
            return json_encode($this->returnMsg);
        }
        if (!$request->filled('name')) {
            $this->returnMsg['code'] = 304;
            $this->returnMsg['msg'] = '项目名为必填项!';
            return json_encode($this->returnMsg);
        }
        if (!$request->filled('customer_id')) {
            $this->returnMsg['code'] = 304;
            $this->returnMsg['msg'] = '联系电话、座机、微信号至少一个必填!';
            return json_encode($this->returnMsg);
        }

        $savedata = [];
        $savedata['name'] = $request->name;
        $request->filled('is_new_customer') && $savedata['is_new_customer'] = $request->is_new_customer;
        $request->filled('type') && $savedata['type'] = $request->type;
        $request->filled('source') && $savedata['source'] = $request->source;
        $request->filled('company') && $savedata['company'] = $request->company;
        $request->filled('address') && $savedata['address'] = $request->address;
        $request->filled('phone') && $savedata['phone'] = $request->phone;
        $request->filled('landline') && $savedata['landline'] = $request->landline;
        $request->filled('wechat') && $savedata['wechat'] = $request->wechat;
        $request->filled('position') && $savedata['position'] = $request->position;
        $request->filled('remarks') && $savedata['remarks'] = $request->remarks;

        if ($request->filled('editid')) {
            $savedata['last_time'] = time();
            $msg = CustomerModel::where('id', $request->editid)->update($savedata);
        } else {
            $savedata['admin_id'] = $this->arr_login_user['id'];
            $savedata['admin_name'] = $this->arr_login_user['name'];
            $savedata['create_time'] = time();
            $msg = CustomerModel::insert($savedata);
        }

        if ($msg == true) {
            $this->returnMsg['msg'] = '成功!';
        } else {
            $this->returnMsg['code'] = 500;
            $this->returnMsg['msg'] = '失败!';
        }

        return json_encode($this->returnMsg);
    }

    /**
     * 获取客户信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function getproject() {
        $customer_table = config('constants.CUSTOMER');

        $customer_id = trim(Input::get('customer_id'));
        $arr_customer_where = [[$customer_table . '.id', $customer_id]];
        $obj_customer = DB::table($customer_table)->where($arr_customer_where)->first();

//            $obj_customer->show_status=$this->customer_status[$obj_customer->status];
//            $obj_customer->show_source=$this->customer_source[$obj_customer->source];
//            $obj_customer->show_type=$this->customer_type[$obj_customer->type];

        if ($obj_customer) {
            $obj_customer->create_time = date('Y-m-d H:i:s', $obj_customer->create_time);
            $obj_customer->last_time = date('Y-m-d H:i:s', $obj_customer->last_time);
            $this->returnMsg['msg'] = '成功!';
            $this->returnMsg['data'] = $obj_customer;
        } else {
            $this->returnMsg['code'] = 500;
            $this->returnMsg['msg'] = '获取失败!';
        }

        return json_encode($this->returnMsg);
    }

}
