<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SecondController;
use App\Model\Projects as ProjectsModel;
use App\Model\Customer as CustomerModel;
use App\Model\Record as RecordModel;

class RecordController extends SecondController {

    public $returnMsg = ['code' => 200, 'data' => [], 'msg' => ''];

    public function __construct() {
        parent::__construct();
    }

    public function index(Request $request) {
        $data['customer'] = Db::table('customer')->whereNull('deleted_at')->orderBy('id', 'desc')->pluck('username', 'id')->toArray();
        $data['adminer'] = Db::table('admin_role')->leftJoin('admin', 'admin_role.admin_id', '=', 'admin.id')
                        ->where([['admin_role.role_id', 2], ['admin.status', 1]])->pluck('admin.name', 'admin.id')->toArray();
        return view('Manage.record', ['title' => '项目沟通记录列表', 'data' => json_encode($data)]);
    }

    public function addrecord(Request $request) {
        if (!$request->isMethod('post')) {
            $this->returnMsg['msg'] = '请求方式有误!';
        } else if (!$request->filled('project_id') || !$request->filled('customer_id')) {
            $this->returnMsg['msg'] = '提交数据有误!';
        } else if (!$request->filled('result')) {
            $this->returnMsg['msg'] = '沟通结果必选填项!';
        }

        if ($this->returnMsg['msg'] != '') {
            $this->returnMsg['code'] = 304;
            return json_encode($this->returnMsg);
        }

        $savedata = [];
        $savedata['project_id'] = $request->project_id;
        $savedata['project_name'] = ProjectsModel::where('id', $request->project_id)->value('name');
        $savedata['customer_id'] = $request->customer_id;
        $savedata['customer_name'] = CustomerModel::where('id', $request->customer_id)->value('username');
        $savedata['record_at'] = strtotime($request->record_at);

        $request->filled('admin_id') && $savedata['admin_id'] = $request->admin_id;
        $request->filled('admin_id') && $savedata['admin_name'] = Db::table('admin')->where('id', $request->admin_id)->value('name');

        $savedata['result'] = $request->result;
        $request->filled('process') && $savedata['process'] = $request->process;
        $request->filled('question') && $savedata['question'] = $request->question;

        if ($request->filled('editid')) {
            $savedata['updated_at'] = time();
            $msg = RecordModel::where('id', $request->editid)->update($savedata);
        } else {
            $savedata['input_id'] = $this->arr_login_user['id'];
            $savedata['input_name'] = $this->arr_login_user['name'];
            $savedata['created_at'] = time();
            $msg = RecordModel::insert($savedata);
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
        $page = $request->filled('page') <= 1 ? 0 : $request->page - 1;
        $limit = $request->filled('limit') ? 10 : $request->limit;
        $where = [];
        $request->filled('project_name') && $where[] = ['records.project_name', 'like', '%' . $request->name . '%'];
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

        if ($request->actiontype == 'notlist') {
            $lists = $lists->orderBy('records.id', 'desc')->get();
        } else {
            $total = $total->count();
            $lists = $lists->orderBy('records.id', 'desc')->skip($page * $limit)->take($limit)->get();
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

}
