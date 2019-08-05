<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\SecondController;
use App\Model\Projects as ProjectsModel;
use App\Model\Customer as CustomerModel;
use App\Model\Package as PackageModel;

class PackageController extends SecondController {

    public $returnMsg = ['code' => 200, 'data' => [], 'msg' => ''];

    public function __construct() {
        parent::__construct();
    }

    //项目包首页
    public function index() {
        $data = [];
        $data['adminer'] = Db::table('admin_role')->leftJoin('admin', 'admin_role.admin_id', '=', 'admin.id')
                        ->where([['admin.status', 1]])->pluck('admin.name', 'admin.id')->toArray();
        $data['project'] = Db::table('project')->whereBetween('status', [1, 10])->whereNull('deleted_at')->orderBy('id', 'desc')->pluck('name', 'id')->toArray();
        return view('Manage.package', ['title' => '项目程序包列表', 'data' => json_encode($data)]);
    }

    // 项目包列表
    public function packagelist(Request $request) {
        $page = $request->page <= 1 ? 0 : $request->page - 1;
        $limit = $request->filled('limit') ? $request->limit : 10 ;
        $where = [];
        $request->filled('project_id') && $where[] = ['packages.project_id', $request->project_id];
        $request->filled('input_id') && $where[] = ['packages.input_id', $request->input_id];

        $total = PackageModel::where($where);
        $lists = PackageModel::selectRaw('packages.*')->where($where);

        if ($request->filled('created_at')) {
            $ctimearr = explode('@', $request->created_at);
            $lists = $lists->whereBetween('packages.created_at', [strtotime($ctimearr[0]), strtotime($ctimearr[1])]);
            $total = $total->whereBetween('packages.created_at', [strtotime($ctimearr[0]), strtotime($ctimearr[1])]);
        }

        // 非超级管理员 只能查看属于自己的数据
        if ($this->arr_login_user['is_super'] != 1) {
            $total = $total->where('packages.input_id', $this->arr_login_user['id']);
            $list = $list->where('packages.input_id', $this->arr_login_user['id']);
        }
        
        if ($request->actiontype == 'notlist') {
            $lists = $lists->orderBy('packages.id', 'desc')->get();
        } else {
            $total = $total->count();
            $lists = $lists->orderBy('packages.id', 'desc')->offset($page * $limit)->take($limit)->get();
            $this->returnMsg['total'] = $total;
        }

        $this->returnMsg['data'] = $lists;
        $this->returnMsg['msg'] = 'success';
        return json_encode($this->returnMsg);

//        $arr_contract_where = $this->arr_login_user['is_super'] == 1 ? [] : [[$project_table . '.status', 1], [$customer_table . '.source', '<>', 4], [$customer_table . '.admin_id', $this->arr_login_user['id']]]; //where条件
    }

    //获取项目包信息
    public function getpackage(Request $request) {
        if (!$request->filled('packages_id')) {
            $this->returnMsg['code'] = 304;
            $this->returnMsg['msg'] = '提交参数有误!';
            return $this->returnMsg;
        }

        $infos = PackageModel::where('id', $request->packages_id)->first();

        if ($infos) {
            $this->returnMsg['msg'] = '成功!';
            $this->returnMsg['data'] = $infos;
        } else {
            $this->returnMsg['code'] = 500;
            $this->returnMsg['msg'] = '获取失败!';
        }

        return json_encode($this->returnMsg);
    }

    // 添加 修改程序包
    public function addpackage(Request $request) {
        if (!$request->isMethod('post')) {
            $this->returnMsg['msg'] = '请求方式有误!';
        } else if (!$request->filled('project_id')) {
            $this->returnMsg['msg'] = '项目为必填项!';
        } 

        if ($this->returnMsg['msg'] != '') {
            $this->returnMsg['code'] = 304;
            return json_encode($this->returnMsg);
        }

        $savedata = [];
        $savedata['project_id'] = $request->project_id;
        $savedata['project_name'] = ProjectsModel::where('id', $request->project_id)->value('name') ;
        
        $request->filled('package_app') && $savedata['package_app'] = $request->package_app;
        $request->filled('package_web') && $savedata['package_web'] = $request->package_web;
        $request->filled('package_sql') && $savedata['package_sql'] = $request->package_sql;
        $request->filled('size_app') && $savedata['app_size'] = $request->size_app;
        $request->filled('size_web') && $savedata['web_size'] = $request->size_web;
        $request->filled('size_sql') && $savedata['sql_size'] = $request->size_sql;
        $request->filled('remarks') && $savedata['remarks'] = $request->remarks;
//return json_encode($savedata);
        if ($request->filled('editid')) {
            $savedata['updated_at'] = time();
            $msg = PackageModel::where('id', $request->editid)->update($savedata);
        } else {
            $savedata['input_id'] = $this->arr_login_user['id'];
            $savedata['input_name'] = $this->arr_login_user['name'];
            $savedata['created_at'] = time();
            $msg = PackageModel::insert($savedata);
        }

        if ($msg == true) {
            $this->returnMsg['msg'] = '成功!';
        } else {
            $this->returnMsg['code'] = 500;
            $this->returnMsg['msg'] = '失败!';
        }

        return json_encode($this->returnMsg);
    }

    //软删除项目包信息
    public function delpackage(Request $request) {
        if (!$request->filled('packages_id')) {
            $this->returnMsg['code'] = 304;
            $this->returnMsg['msg'] = '提交参数有误!';
            return $this->returnMsg;
        }

        $infos = PackageModel::where('id', $request->packages_id)->delete();

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
    public function uppackagefiles(Request $request) {
//        ini_set('post_max_size', '300M');
//        ini_set('upload_max_filesize', '300M');
//        ini_set('max_input_time', '300');
        if (!$request->file('packagesfiles')->isValid()) {
            $this->returnMsg['code'] = 304;
            $this->returnMsg['msg'] = '上传的文件无效!';
            return $this->returnMsg;
        }
        
        if($request->packagesfiles->getClientSize() >= 1048576 * 500){
            $this->returnMsg['code'] = 304;
            $this->returnMsg['msg'] = '上传的文件太大!';
            return $this->returnMsg;
        }
        
        $filesize = $request->packagesfiles->getClientSize();
        
        $savefile = Storage::disk('public')->put('packages/' . date('Ym'), $request->packagesfiles);
        $this->returnMsg['data'] = [
            'path' => Storage::disk('local')->url($savefile)
             , 'size' => round($filesize / 1048576, 2)  
                ];

        return json_encode($this->returnMsg);
    }

}
