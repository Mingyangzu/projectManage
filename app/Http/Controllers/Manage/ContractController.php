<?php
    namespace App\Http\Controllers\Manage;
    use App\Http\Controllers\SecondController;
    use Illuminate\Support\Facades\Input;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Pagination\LengthAwarePaginator;
    use Illuminate\Support\Facades\Validator;
    use App\Rules\verfcount;
    use App\Rules\verfmoney;
    class ContractController extends SecondController
    {
        public function __construct()
        {
            parent::__construct();
        }

        /**
         * 合同首页
         * @author tuomeikeji
         * @time 2019-04-19
         */
        public function index()
        {
            $arr_var=['title'=>'合同管理'];

            $contract_table=config('constants.CONTRACT');
            $project_table=config('constants.PROJECT');
            $customer_table=config('constants.CUSTOMER');
            $arr_post_data=Input::get();

            $arr_var['arr_post_data']=$arr_post_data;
            foreach($arr_post_data as $k=>$v)
            {
                $arr_post_data[$k]=trim($v);
            }

            $arr_contract_where=$this->arr_login_user['is_super']==1 ? [] : [[$project_table.'.status',1],[$customer_table.'.source','<>',4],[$customer_table.'.admin_id',$this->arr_login_user['id']]];//where条件

            if(isset($arr_post_data['p_id']))
            {
                $arr_contract_where[]=[$contract_table.'.project_id','=',$arr_post_data['p_id']];
            }
            if(isset($arr_post_data['select_project_name']) && $arr_post_data['select_project_name']!='')
            {
                $arr_contract_where[]=[$project_table.'.name','=',$arr_post_data['select_project_name']];
            }
            if(isset($arr_post_data['select_contract_title']) && $arr_post_data['select_contract_title']!='')
            {
                $arr_contract_where[]=[$contract_table.'.title','=',$arr_post_data['select_contract_title']];
            }
            if(isset($arr_post_data['select_contract_status']) && $arr_post_data['select_contract_status']!=2)
            {
                $arr_contract_where[]=[$contract_table.'.status','=',$arr_post_data['select_contract_status']];
            }
            if(isset($arr_post_data['select_contract_money']) && $arr_post_data['select_contract_money']!='')
            {
                $arr_contract_where[]=[$contract_table.'.money','=',$arr_post_data['select_contract_money']];
            }
            if(isset($arr_post_data['select_contract_time']) && $arr_post_data['select_contract_time']!='')
            {
                $arr_contract_where[]=[$contract_table.'.contract_time','=',strtotime($arr_post_data['select_contract_time'])];
            }

            $current_page = Input::get("page",1);
    //        DB::connection()->enableQueryLog();
            $arr_data = DB::table($contract_table)->join($project_table,$project_table.'.id','=',$contract_table.'.project_id')->join($customer_table,$customer_table.'.id','=',$project_table.'.customer_id')->select($contract_table.'.*',$project_table.'.name as project_name',$customer_table.'.company')->where($arr_contract_where)->orderBy('id', 'desc')->get();
    //var_dump($arr_data);
    //         $last_sql=DB::getQueryLog();
    // var_dump($last_sql);
            $items = array_slice(json_decode(json_encode($arr_data),true),($current_page-1)*$this->pub_per_page,$this->pub_per_page);
            $arr_var['contract_rows']=count($arr_data);
            $arr_var['arr_contract'] = new LengthAwarePaginator($items, $arr_var['contract_rows'],$this->pub_per_page);
            $arr_var['arr_contract_status']=$this->contract_status;
            $arr_var['app_url']=env('APP_URL');

            return view('Admin.contract_index',$arr_var);
        }

        /**
         * 获取合同信息
         * @author tuomeikeji
         * @time 2019-04-18
         */
        public function get_contract()
        {
            $contract_table=config('constants.CONTRACT');
            $project_table=config('constants.PROJECT');

            $contract_id=trim(Input::get('contract_id'));
            $arr_contract_where=[[$contract_table.'.id',$contract_id]];
            $obj_contract_records=DB::table($contract_table)->join($project_table,$project_table.'.id','=',$contract_table.'.project_id')->select($contract_table.'.*',$project_table.'.name as project_name')->where($arr_contract_where)->first();
            $obj_contract_records->take_effect_time=date('Y-m-d H:i',$obj_contract_records->take_effect_time);
            $obj_contract_records->end_time=date('Y-m-d H:i',$obj_contract_records->end_time);
            $obj_contract_records->contract_time=date('Y-m-d H:i',$obj_contract_records->contract_time);
            $obj_contract_records->contract_show_url=env('APP_URL').'/'.$obj_contract_records->url;
//var_dump($obj_contract_records);die;
    //            var_dump(json_encode($obj_project));die;
            return $obj_contract_records==NULL ? 0 : json_encode($obj_contract_records);
        }

        /**
         *更新合同信息
         * @author tuomeikeji
         * @time 2019-04-18
         */
        public function update_contract()
        {
            $contract_table=config('constants.CONTRACT');
            $project_table=config('constants.PROJECT');

            $arr_update=self::pub_add_update();

            $arr_project_where=[['name',$arr_update['project_name']],['status',1]];
            $obj_project=DB::table($project_table)->select('id')->where($arr_project_where)->first();//查询项目是否存在
            if($obj_project==NULL)
            {
                $this->arr_return['message']='项目不存在';
                $this->arr_return['error_input']='modal_project_name';
                return json_encode($this->arr_return);
            }

            $arr_update['project_id']=$obj_project->id;
            $arr_update['last_time'] = time();
            unset($arr_update['project_name']);

            $contract_id=$arr_update['contract_id'];
            $arr_contract_where=[[$contract_table.'.title','=',$arr_update['title']],[$contract_table.'.project_id','=',$arr_update['project_id']],[$contract_table.'.id','<>',$contract_id]];
            $obj_is_contract=DB::table($contract_table)->select('id')->where($arr_contract_where)->first();//查询合同是否存在
            if(isset($obj_is_contract))
            {
                $this->arr_return['message']='该合同已存在';
                $this->arr_return['error_input']='';
                return json_encode($this->arr_return);
            }
            unset($arr_update['contract_id']);
            DB::table($contract_table)
                ->where('id', $contract_id)
                ->update($arr_update);
            $this->arr_return['status'] = 1;
            $this->arr_return['message'] = '修改成功';
            return json_encode($this->arr_return);
        }

        /**
         *添加合同
         * @author tuomeikeji
         * @time 2019-04-18
         */
        public function add_contract()
        {
            $contract_table=config('constants.CONTRACT');
            $project_table=config('constants.PROJECT');

            $arr_update=self::pub_add_update();

            $arr_project_where=[['name',$arr_update['project_name']],['status',1]];
            $obj_project=DB::table($project_table)->select('id')->where($arr_project_where)->first();//查询项目是否存在
            if($obj_project==NULL)
            {
                $this->arr_return['message']='项目不存在';
                $this->arr_return['error_input']='modal_project_name';
                return json_encode($this->arr_return);
            }
            $arr_update['project_id']=$obj_project->id;
            $arr_update['last_time'] = time();
            unset($arr_update['project_name']);

    //        unset($arr_update['collection_id']);
            $arr_contract_where=[[$contract_table.'.title','=',$arr_update['title']],[$contract_table.'.project_id','=',$arr_update['project_id']]];

            $obj_is_contract=DB::table($contract_table)->select('id')->where($arr_contract_where)->first();//查询合同是否存在
            if(isset($obj_is_contract))
            {
                $this->arr_return['message']='该合同已存在';
                $this->arr_return['error_input']='';
                return json_encode($this->arr_return);
            }
            $arr_update['create_time'] = time();
            unset($arr_update['contract_id']);
            $insert_id=DB::table($contract_table)->insertGetId($arr_update);
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
         * 合同收集
         * @author tuomeikeji
         * @time 2019-04-18
         */
        private function pub_add_update()
        {
            $arr_update=Input::get();
//            var_dump($arr_update);die;
            unset($arr_update['_token']);
            foreach($arr_update as $k=>$v)
            {
                $arr_update[$k]=trim(htmlspecialchars($v));
            }
            $arr_rule=[
                'project_name'=>['required',new verfcount(100,'项目名字','modal_project_name')],
                'title'=>['required',new verfcount(100,'合同标题','modal_contract_title')],
                'describe'=>new verfcount(20000,'合同描述','modal_contract_describe'),
                'money'=>['required','numeric',new verfmoney('modal_contract_money')],
                'take_effect_time'=>['required'],
                'end_time'=>['required'],
                'url'=>['required'],
                'contract_time'=>['required'],
            ];
            $arr_message=[
                'project_name.*'=>['项目名不能为空','modal_project_name'],
                'title.*'=>['合同标题不能为空','modal_contract_title'],
                'describe.*'=>['合同描述不能超过20000个字','modal_contract_describe'],
                'take_effect_time.*'=>['合同生效时间必填','modal_contract_take_effect_time'],
                'end_time.*'=>['合同截止时间必填','modal_contract_end_time'],
                'url.*'=>['合同附件不能为空',''],
                'contract_time.*'=>['签约时间必须选','modal_contract_time'],
                'money.*'=>['合同金额必须填且必须为数字','modal_contract_money'],
            ];

            Validator::make($arr_update,$arr_rule,$arr_message)->validate();

            $arr_update['take_effect_time']=strtotime($arr_update['take_effect_time']);
            $arr_update['end_time']=strtotime($arr_update['end_time']);
            $arr_update['contract_time']=strtotime($arr_update['contract_time']);
            return $arr_update;
        }

        /**
         * 上传合同
         * @author tuomeikeji
         * @time 2019-04-18
         */
        public function contract_url()
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
                    $this->arr_return['file_address']=$url_src.$file['name'];
                    return json_encode($this->arr_return);
                }

                $this->arr_return['message']='上传失败';
                return json_encode($this->arr_return);
            }

            $this->arr_return['message']='上传错误';
            return json_encode($this->arr_return);
        }
    }