<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Support\Facades\Input;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use App\Rules\verfcount;
use App\Rules\verfmoney;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\SecondController;
use App\Model\Projects as ProjectsModel;
use App\Model\Customer as CustomerModel;
use App\Model\Contract as ContractModel;

class ContractController extends SecondController {

    public $returnMsg = ['code' => 200, 'data' => [], 'msg' => ''];

    public function __construct() {
        parent::__construct();
    }

    //合同首页
    public function index() {
        $data = [];
        $data['customer'] = Db::table('customer')->whereNull('deleted_at')->orderBy('id', 'desc')->pluck('username', 'id')->toArray();
//        $data['adminer'] = Db::table('admin_role')->leftJoin('admin', 'admin_role.admin_id', '=', 'admin.id')
//                        ->where([['admin_role.role_id', 2], ['admin.status', 1]])->pluck('admin.name', 'admin.id')->toArray();
        $data['project'] = Db::table('project')->whereBetween('status', [1, 10])->whereNull('deleted_at')->orderBy('id', 'desc')->pluck('name', 'id')->toArray();
        return view('Manage.contract', ['title' => '项目合同列表', 'data' => json_encode($data)]);
    }

    // 合同信息列表
    public function contractlist(Request $request) {
        $page = $request->page <= 1 ? 0 : $request->page - 1;
        $limit = $request->filled('limit') ? 10 : $request->limit;
        $where = [];
        $request->filled('project_id') && $where[] = ['project.id', $request->project_id];
        $request->filled('title') && $where[] = ['contract.title', 'like', '%' . $request->title . '%'];
        $request->filled('customer_id') && $where[] = ['project.customer_id', $request->customer_id];

        $total = ContractModel::where($where)->leftJoin('project', 'project.id', '=', 'contract.project_id');
        $lists = ContractModel::selectRaw('contract.*, project.name, project.customer_name')->where($where)
                ->leftJoin('project', 'project.id', '=', 'contract.project_id');

        if ($request->filled('take_effect_time')) {
            $ctimearr = explode('@', $request->take_effect_time);
            $lists = $lists->whereBetween('contract.take_effect_time', [strtotime($ctimearr[0]), strtotime($ctimearr[1])]);
            $total = $total->whereBetween('contract.take_effect_time', [strtotime($ctimearr[0]), strtotime($ctimearr[1])]);
        }

        if ($request->actiontype == 'notlist') {
            $lists = $lists->orderBy('contract.id', 'desc')->get();
        } else {
            $total = $total->count();
            $lists = $lists->orderBy('contract.id', 'desc')->offset($page * $limit)->take($limit)->get();
            $this->returnMsg['total'] = $total;
        }

        $this->returnMsg['data'] = $lists;
        $this->returnMsg['msg'] = 'success';
        return json_encode($this->returnMsg);

//        $arr_contract_where = $this->arr_login_user['is_super'] == 1 ? [] : [[$project_table . '.status', 1], [$customer_table . '.source', '<>', 4], [$customer_table . '.admin_id', $this->arr_login_user['id']]]; //where条件
    }

    //获取合同信息
    public function getcontract(Request $request) {
        if (!$request->filled('contract_id')) {
            $this->returnMsg['code'] = 304;
            $this->returnMsg['msg'] = '提交参数有误!';
            return $this->returnMsg;
        }

        $infos = ContractModel::where('id', $request->contract_id)->first();

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

    // 添加 修改合同
    public function addcontract(Request $request) {
        if (!$request->isMethod('post')) {
            $this->returnMsg['msg'] = '请求方式有误!';
        } else if (!$request->filled('title')) {
            $this->returnMsg['msg'] = '合同标题为必填项!';
        } else if (!$request->filled('project_id')) {
            $this->returnMsg['msg'] = '项目为必选项!';
        } else if (!$request->filled('money')) {
            $this->returnMsg['msg'] = '合同金额为必填项!';
        }

        if ($this->returnMsg['msg'] != '') {
            $this->returnMsg['code'] = 304;
            return json_encode($this->returnMsg);
        }


        $savedata = [];
        $savedata['title'] = $request->title;
        $savedata['project_id'] = $request->project_id;
        $savedata['money'] = $request->money;

        $request->filled('take_effect_time') && $savedata['take_effect_time'] = strtotime($request->take_effect_time);
        $request->filled('contract_time') && $savedata['contract_time'] = strtotime($request->contract_time);
        $request->filled('end_time') && $savedata['end_time'] = strtotime($request->end_time);
        $request->filled('describe') && $savedata['describe'] = $request->describe;
        $request->filled('url') && $savedata['url'] = $request->url;

        if ($request->filled('editid')) {
            $savedata['last_time'] = time();
            $msg = ContractModel::where('id', $request->editid)->update($savedata);
        } else {
            $savedata['input_id'] = $this->arr_login_user['id'];
            $savedata['input_name'] = $this->arr_login_user['name'];
            $savedata['create_time'] = time();
            $msg = ContractModel::insert($savedata);
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

    //软删除合同信息
    public function delcontract(Request $request) {
        if (!$request->filled('contract_id')) {
            $this->returnMsg['code'] = 304;
            $this->returnMsg['msg'] = '提交参数有误!';
            return $this->returnMsg;
        }

        $infos = ContractModel::where('id', $request->contract_id)->delete();

        if ($infos) {
            $this->returnMsg['msg'] = '成功!';
            $this->returnMsg['data'] = $infos;
        } else {
            $this->returnMsg['code'] = 500;
            $this->returnMsg['msg'] = '失败!';
        }

        return json_encode($this->returnMsg);
    }

    // 上传合同文件
    public function upcontractfiles(Request $request) {
        if (!$request->file('contractfiles')->isValid()) {
            $this->returnMsg['code'] = 304;
            $this->returnMsg['msg'] = '上传的文件无效!';
            return $this->returnMsg;
        }

        $savefile = Storage::disk('public')->put('contract/' . date('Ym'), $request->contractfiles);
        $this->returnMsg['data'] = Storage::disk('local')->url($savefile);

        return json_encode($this->returnMsg);
    }

}
