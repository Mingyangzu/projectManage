<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\SecondController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use App\Rules\verfmoney;
use App\Rules\verfcount;
class FinanceController extends SecondController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 财务首页
     * @author tuomeikeji
     * @time 2019-04-19
     */
    public function finance_index()
    {
        $arr_var=['title'=>'项目财务'];

        $collection_records_table=config('constants.COLLECTION_RECORDS');
        $contract_table=config('constants.CONTRACT');
        $project_table=config('constants.PROJECT');
        $customer_table=config('constants.CUSTOMER');
        $admin_table=config('constants.ADMIN');

        $arr_post_data=Input::get();

        $arr_var['arr_post_data']=$arr_post_data;
        foreach($arr_post_data as $k=>$v)
        {
            $arr_post_data[$k]=trim($v);
        }

        $arr_collection_where=[];//where条件

        if(isset($arr_post_data['c_id']))
        {
            $arr_collection_where[]=[$collection_records_table.'.contract_id','=',$arr_post_data['c_id']];
        }
        if(isset($arr_post_data['select_project_name']) && $arr_post_data['select_project_name']!='')
        {
            $arr_collection_where[]=[$project_table.'.name','=',$arr_post_data['select_project_name']];
        }
        if(isset($arr_post_data['select_customer_admin']) && $arr_post_data['select_customer_admin']!=0)
        {
            $arr_collection_where[]=[$customer_table.'.admin_id','=',$arr_post_data['select_customer_admin']];
        }
        if(isset($arr_post_data['select_contract_money']) && $arr_post_data['select_contract_money']!='')
        {
            $arr_collection_where[]=[$contract_table.'.money','=',$arr_post_data['select_contract_money']];
        }
        if(isset($arr_post_data['select_collection_status']) && $arr_post_data['select_collection_status']!=2)
        {
            $arr_collection_where[]=[$collection_records_table.'.status','=',$arr_post_data['select_collection_status']];
        }
        if(isset($arr_post_data['select_pay_method']) && $arr_post_data['select_pay_method']!=100)
        {
            $arr_collection_where[]=[$collection_records_table.'.pay_method','=',$arr_post_data['select_pay_method']];
        }
        if(isset($arr_post_data['select_contract_name']) && $arr_post_data['select_contract_name']!='')
        {
            $arr_collection_where[]=[$contract_table.'.title','=',$arr_post_data['select_contract_name']];
        }
        if(isset($arr_post_data['select_collection_name']) && $arr_post_data['select_collection_name']!=100)
        {
            $arr_collection_where[]=[$collection_records_table.'.name','=',$arr_post_data['select_collection_name']];
        }
        if(isset($arr_post_data['select_pay_time']) && $arr_post_data['select_pay_time']!='')
        {
            $arr_collection_where[]=[$collection_records_table.'.pay_time','=',strtotime($arr_post_data['select_pay_time'])];
        }
//var_dump($arr_project_where);
        $arr_var['arr_step']=$this->step_list();
        $arr_var['arr_type']=$this->type_list();

        $current_page = Input::get("page",1);
//        DB::connection()->enableQueryLog();
        $arr_data = DB::table($collection_records_table)->join($contract_table,$contract_table.'.id','=',$collection_records_table.'.contract_id')->join($project_table,$project_table.'.id','=',$contract_table.'.project_id')->join($customer_table,$customer_table.'.id','=',$project_table.'.customer_id')->join($admin_table,$admin_table.'.id','=',$customer_table.'.admin_id')->select($collection_records_table.'.*',$admin_table.'.name as admin_name',$project_table.'.name as project_name',$contract_table.'.money as contract_money',$contract_table.'.title as contract_name')->where($arr_collection_where)->orderBy('id', 'desc')->get();
//var_dump($arr_data);
//         $last_sql=DB::getQueryLog();
// var_dump($last_sql);
        $items = array_slice(json_decode(json_encode($arr_data),true),($current_page-1)*$this->pub_per_page,$this->pub_per_page);
        $arr_var['collection_records_rows']=count($arr_data);
        $arr_var['arr_collection_records'] = new LengthAwarePaginator($items, $arr_var['collection_records_rows'],$this->pub_per_page);

        $arr_var['arr_admin_list']=DB::table($admin_table)->where('status',1)->get();
        $arr_var['collection_status']=$this->connection_records;
        $arr_var['connection_records_name']=$this->connection_records_name;
        $arr_var['project_pay_status']=$this->project_pay_status;
        $arr_var['pay_method']=$this->pay_method;
//var_dump($arr_var);die;
        return view('Admin.finance_index',$arr_var);
    }

    /**
     * 获取收款记录信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function get_collection_records()
    {
        $contract_table=config('constants.CONTRACT');
        $collection_records_table=config('constants.COLLECTION_RECORDS');

        $collection_id=trim(Input::get('collection_id'));
        $arr_collection_where=[[$collection_records_table.'.id',$collection_id]];
        $obj_collection_records=DB::table($collection_records_table)->join($contract_table,$contract_table.'.id','=',$collection_records_table.'.contract_id')->select($collection_records_table.'.*',$contract_table.'.title as contract_name')->where($arr_collection_where)->first();
//            var_dump(json_encode($obj_project));die;
        return $obj_collection_records==NULL ? 0 : json_encode($obj_collection_records);
    }

    /**
     * 更新收款信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function update_collection()
    {
        $contract_table=config('constants.CONTRACT');
        $collection_records_table=config('constants.COLLECTION_RECORDS');

        $arr_update=self::pub_add_update();
        $arr_contract_where=[['title',$arr_update['contract_name']],['status',1]];
        $obj_contract=DB::table($contract_table)->select('id')->where($arr_contract_where)->first();//查询合同是否存在
        if($obj_contract==NULL)
        {
            $this->arr_return['message']='合同不存在';
            $this->arr_return['error_input']='modal_contract_name';
            return json_encode($this->arr_return);
        }

        $arr_update['contract_id']=$obj_contract->id;
        $arr_update['last_time'] = time();
        unset($arr_update['contract_name']);

        $collection_id=$arr_update['collection_id'];
        $arr_connection_where=[[$collection_records_table.'.name','=',$arr_update['name']],[$collection_records_table.'.contract_id','=',$arr_update['contract_id']],[$collection_records_table.'.id','<>',$collection_id]];
        $obj_is_connection=DB::table($collection_records_table)->select('id')->where($arr_connection_where)->first();//查询收款记录是否存在
        if(isset($obj_is_connection))
        {
            $this->arr_return['message']='该收款记录已存在';
            $this->arr_return['error_input']='';
            return json_encode($this->arr_return);
        }
        unset($arr_update['collection_id']);
        DB::table($collection_records_table)
            ->where('id', $collection_id)
            ->update($arr_update);
        $this->arr_return['status'] = 1;
        $this->arr_return['message'] = '修改成功';
        return json_encode($this->arr_return);
    }

    /**
     * 添加收款信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function add_collection()
    {
        $contract_table=config('constants.CONTRACT');
        $collection_records_table=config('constants.COLLECTION_RECORDS');
        $arr_update=self::pub_add_update();

        $arr_contract_where=[['title',$arr_update['contract_name']],['status',1]];
        $obj_contract=DB::table($contract_table)->select('id')->where($arr_contract_where)->first();//查询合同是否存在
        if($obj_contract==NULL)
        {
            $this->arr_return['message']='合同不存在';
            $this->arr_return['error_input']='modal_contract_name';
            return json_encode($this->arr_return);
        }
        $arr_update['contract_id']=$obj_contract->id;
        $arr_update['last_time'] = time();
        unset($arr_update['contract_name']);

//        unset($arr_update['collection_id']);
        $arr_connection_where=[[$collection_records_table.'.name','=',$arr_update['name']],[$collection_records_table.'.contract_id','=',$arr_update['contract_id']]];

        $obj_is_connection=DB::table($collection_records_table)->select('id')->where($arr_connection_where)->first();//查询收款记录是否存在
        if(isset($obj_is_connection))
        {
            $this->arr_return['message']='该收款记录已存在';
            $this->arr_return['error_input']='';
            return json_encode($this->arr_return);
        }
        $arr_update['create_time'] = time();
        $insert_id=DB::table($collection_records_table)->insertGetId($arr_update);
        if($insert_id>0)
        {
            $this->arr_return['status'] = 1;
            $this->arr_return['message'] = '添加成功';
            return json_encode($this->arr_return);
        }
        $this->arr_return['message'] = '添加失败';
        return json_encode($this->arr_return);
    }

    /**
     * 收款信息收集
     * @author tuomeikeji
     * @time 2019-04-18
     */
    private function pub_add_update()
    {
        $arr_update=Input::get();
//        var_dump($arr_update);die;
        unset($arr_update['_token']);
        foreach($arr_update as $k=>$v)
        {
            $arr_update[$k]=trim(htmlspecialchars($v));
        }
        $arr_rule=[
            'contract_name'=>['required',new verfcount(100,'合同名字','modal_contract_name')],
            'pay_money'=>['required','numeric',new verfmoney('modal_collection_money')],
        ];
        $arr_message=[
            'contract_name.*'=>['合同名不能为空','modal_contract_name'],
            'pay_money.*'=>['收款金额不能为空并且必须为数字','modal_collection_money'],
        ];

        Validator::make($arr_update,$arr_rule,$arr_message)->validate();
        $arr_update['pay_time']=$arr_update['status']==0 ? 0 : strtotime(date('Y-m-d',time()));
        return $arr_update;
    }

    /**
     * 支出首页
     * @author tuomeikeji
     * @time 2019-05-06
     */
    public function expenditure_index()
    {
        $arr_var=['title'=>'财务支出'];

        $expenditure_table=config('constants.EXPENDITURE');
        $admin_table=config('constants.ADMIN');

        $arr_post_data=Input::get();

        $arr_var['arr_post_data']=$arr_post_data;
        foreach($arr_post_data as $k=>$v)
        {
            $arr_post_data[$k]=trim($v);
        }

        $arr_expenditure_where=[];//where条件

        if(isset($arr_post_data['select_expenditure_name']) && $arr_post_data['select_expenditure_name']!=0)
        {
            $arr_expenditure_where[]=[$expenditure_table.'.name','=',$arr_post_data['select_expenditure_name']];
        }
        if(isset($arr_post_data['select_expenditure_status']) && $arr_post_data['select_expenditure_status']!=2)
        {
            $arr_expenditure_where[]=[$expenditure_table.'.status','=',$arr_post_data['select_expenditure_status']];
        }
        if(isset($arr_post_data['select_expenditure_money']) && $arr_post_data['select_expenditure_money']!='')
        {
            $arr_expenditure_where[]=[$expenditure_table.'.pay_money','=',$arr_post_data['select_expenditure_money']];
        }
        if(isset($arr_post_data['select_pay_method']) && $arr_post_data['select_pay_method']!=100)
        {
            $arr_expenditure_where[]=[$expenditure_table.'.pay_method','=',$arr_post_data['select_pay_method']];
        }
        if(isset($arr_post_data['select_consumer']) && $arr_post_data['select_consumer']!=0)
        {
            $arr_expenditure_where[]=[$expenditure_table.'.consumer','=',$arr_post_data['select_consumer']];
        }
        if(isset($arr_post_data['select_pay_time_start']) && $arr_post_data['select_pay_time_start']!='')
        {
            if(!isset($arr_post_data['select_pay_time_end']) || $arr_post_data['select_pay_time_end']=='')
            {
                $arr_expenditure_where[]=[$expenditure_table.'.pay_time','>=',strtotime($arr_post_data['select_pay_time_start'])];
            }
        }
        if(isset($arr_post_data['select_pay_time_start']) && $arr_post_data['select_pay_time_start']!='' && isset($arr_post_data['select_pay_time_end']) && $arr_post_data['select_pay_time_end']!='')
        {
            $arr_expenditure_where[]=[$expenditure_table.'.pay_time','>=',strtotime($arr_post_data['select_pay_time_start'])];
            $arr_expenditure_where[]=[$expenditure_table.'.pay_time','<=',strtotime($arr_post_data['select_pay_time_end'])];
        }

        $current_page = Input::get("page",1);
        $arr_data = DB::table($expenditure_table)->join($admin_table,$admin_table.'.id','=',$expenditure_table.'.operator')->join($admin_table.' as consumer','consumer.id','=',$expenditure_table.'.consumer')->select($expenditure_table.'.*',$admin_table.'.name as admin_name','consumer.name as consumer_name')->where($arr_expenditure_where)->orderBy('id', 'desc')->get();
        $items = array_slice(json_decode(json_encode($arr_data),true),($current_page-1)*$this->pub_per_page,$this->pub_per_page);
        $arr_var['expenditure_rows']=count($arr_data);
        $arr_var['arr_expenditure'] = new LengthAwarePaginator($items, $arr_var['expenditure_rows'],$this->pub_per_page);

        $arr_var['expenditure_status']=$this->expenditure_status;
        $arr_var['arr_expenditure_name']=$this->expenditure_name;
        $arr_var['pay_method']=$this->pay_method;
        $arr_var['arr_admin_list']=DB::table($admin_table)->where('status',1)->get();
//var_dump($arr_var);die;
        return view('Admin.expenditure_index',$arr_var);
    }

    /**
     * 获取支出信息
     * @author tuomeikeji
     * @time 2019-05-06
     */
    public function get_expenditure()
    {
        $expenditure_table=config('constants.EXPENDITURE');
        $admin_table=config('constants.ADMIN');

        $expenditure_id=trim(Input::get('expenditure_id'));
        $arr_expenditure_where=[[$expenditure_table.'.id',$expenditure_id]];
        $obj_expenditure_records=DB::table($expenditure_table)->join($admin_table,$admin_table.'.id','=',$expenditure_table.'.consumer')->select($expenditure_table.'.*',$admin_table.'.name as consumer_name')->where($arr_expenditure_where)->first();
//            var_dump(json_encode($obj_project));die;
        return $obj_expenditure_records==NULL ? 0 : json_encode($obj_expenditure_records);
    }

    /**
     * 更新支出信息
     * @author tuomeikeji
     * @time 2019-05-06
     */
    public function update_expenditure()
    {
        $expenditure_table=config('constants.EXPENDITURE');

        $arr_update=self::pub_add_update_expenditure();

        $expenditure_id=$arr_update['expenditure_id'];
//var_dump($arr_update);die;
        $arr_update['last_time'] = strtotime(date('Y-m-d',time()));
        $arr_update['operator'] =$this->arr_login_user['id'];
        unset($arr_update['expenditure_id']);
        DB::table($expenditure_table)
            ->where('id', $expenditure_id)
            ->update($arr_update);
        $this->arr_return['status'] = 1;
        $this->arr_return['message'] = '修改成功';
        return json_encode($this->arr_return);
    }

    /**
     * 添加支出
     * @author tuomeikeji
     * @time 2019-05-06
     */
    public function add_expenditure()
    {
        $expenditure_table=config('constants.EXPENDITURE');

        $arr_update=self::pub_add_update_expenditure();

        $arr_update['last_time'] = strtotime(date('Y-m-d',time()));
        $arr_update['create_time'] = strtotime(date('Y-m-d',time()));
        $arr_update['operator'] =$this->arr_login_user['id'];
        unset($arr_update['expenditure_id']);
        $insert_id=DB::table($expenditure_table)->insertGetId($arr_update);
        if($insert_id>0)
        {
            $this->arr_return['status'] = 1;
            $this->arr_return['message'] = '添加成功';
            return json_encode($this->arr_return);
        }
        $this->arr_return['message'] = '添加失败';
        return json_encode($this->arr_return);
    }

    /**
     * 支出信息收集
     * @author tuomeikeji
     * @time 2019-05-06
     */
    private function pub_add_update_expenditure()
    {
        $arr_update=Input::get();
//        var_dump($arr_update);die;
        unset($arr_update['_token']);
        foreach($arr_update as $k=>$v)
        {
            $arr_update[$k]=trim(htmlspecialchars($v));
        }
        $arr_rule=[
            'pay_money'=>['required','numeric',new verfmoney('modal_expenditure_money')],
            'remarks'=>[new verfcount(500,'备注','modal_expenditure_remarks')],
        ];
        $arr_message=[
            'pay_money.*'=>['支出金额不能为空并且必须为数字','modal_expenditure_money'],
        ];

        Validator::make($arr_update,$arr_rule,$arr_message)->validate();
        $arr_update['pay_time']=$arr_update['status']==0 ? 0 : strtotime(date('Y-m-d',time()));
        return $arr_update;
    }

    /**
     * 上传付款凭证
     * @author tuomeikeji
     * @time 2019-05-07
     */
    public function expenditure_url()
    {
        $file = $_FILES['ajax_upload_files'];


        if ($file['error'] == 0)
        {
            $url_src='uploads/'.date('Ymd',time()).'/';
            $static_dir = FCPATH.$url_src;
            if(!is_dir($static_dir))
            {
                $create_dir=mkdir($static_dir,0777,true);
                if(!$create_dir)
                {
                    $this->arr_return['message']='文件未存储';
                    return json_encode($this->arr_return);
                }
            }
            $is_upload=move_uploaded_file($file['tmp_name'],$static_dir.$file['name']);
            if($is_upload)
            {
                $this->arr_return['status']=1;
                $this->arr_return['message']='上传成功';
                $this->arr_return['file_name']=$file['name'];
                $this->arr_return['file_address']=$_SERVER["HTTP_HOST"].'/'.$url_src.$file['name'];
                return json_encode($this->arr_return);
            }

            $this->arr_return['message']='上传失败';
            return json_encode($this->arr_return);
        }

        $this->arr_return['message']='上传错误';
        return json_encode($this->arr_return);
    }
}