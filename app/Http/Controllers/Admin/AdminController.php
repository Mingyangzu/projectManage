<?php
    namespace App\Http\Controllers\Admin;
    use App\Http\Controllers\SecondController;
    use Illuminate\Support\Facades\Input;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Pagination\LengthAwarePaginator;
    use Illuminate\Support\Facades\Validator;
    use App\Rules\verfphone;
    use App\Rules\verfmoney;
    use App\Rules\verfint;
    use App\Rules\verfcount;
    class AdminController extends SecondController
    {
        private  $select_phone='';
        public function __construct()
        {
            parent::__construct();
        }

        /**
         * 后台登陆页面
         * @author tuomeikeji
         * @time 2019-04-16
         */
        public function login()
        {
            return view('Admin.login',['title'=>'后台登陆页面','is_nav'=>0]);
        }

        /**
         * 登录验证
         * @author tuomeikeji
         * @time 2019-04-16
         */
        public function login_check()
        {
            $admin_table = config('constants.ADMIN');
            $arr_post=Input::get();
            foreach($arr_post as $k=>$v)
            {
                $arr_post[$k]=trim($v);
            }
//            DB::connection()->enableQueryLog();
            $obj_user=DB::table($admin_table)->where([['name',$arr_post['username']],['status',1]])->orWhere([['email',$arr_post['username']],['status',1]])->orWhere('phone',$arr_post['username'])->first();
// $last_sql=DB::getQueryLog();
// var_dump($last_sql);die;
            if($obj_user!=NULL)
            {
                if(create_password($arr_post['password'],$obj_user->salt)!=$obj_user->password)
                {
                    $this->arr_return['message']='密码错误';
                    $this->arr_return['error_input']='password';
                    return json_encode($this->arr_return);
                }

                session(['user_info' => $obj_user]);//存储登录用户信息
                $this->arr_return['status']=1;
                $this->arr_return['message']='验证通过';
                return json_encode($this->arr_return);
            }
            $this->arr_return['message']='用户不存在';
            $this->arr_return['error_input']='username';
            return json_encode($this->arr_return);
        }

        /**
         * 修改密码
         * @author tuomeikeji
         * @time 2019-04-16
         */
        public function modify_password()
        {
            $admin_table = config('constants.ADMIN');
            $arr_post=Input::get();
            foreach($arr_post as $k=>$v)
            {
                $arr_post[$k]=trim($v);
            }

            if(strlen($arr_post['new_password'])<6 || strlen($arr_post['new_password'])>16)
            {
                $this->arr_return['message']='密码不能小于6位并且不能大于16位';
                $this->arr_return['error_input']='modal_new_password';
                return json_encode($this->arr_return);
            }

            if($arr_post['new_password']!=$arr_post['new_password_ok'])
            {
                $this->arr_return['message']='两次输入密码不一致';
                $this->arr_return['error_input']='modal_new_password_ok';
                return json_encode($this->arr_return);
            }

            $salt=str_random(6);
            $arr_update['salt']=$salt;
            $arr_update['last_time']=time();
            $arr_update['password']=create_password($arr_post['new_password'],$salt);
            DB::table($admin_table)
                ->where('id', $arr_post['admin_id'])
                ->update($arr_update);
            $this->arr_return['status']=1;
            $this->arr_return['message']='修改成功';
            return json_encode($this->arr_return);
        }

        /**
         * 退出系统
         * @author tuomeikeji
         * @time 2019-04-16
         */
        public function sign_out()
        {
            session()->forget('user_info');
            redirect('/')->send();
        }

        /**
         * 项目管理
         * @author tuomeikeji
         * @time 2019-04-16
         */
        public function project()
        {
            $arr_var=['title'=>'项目管理'];

            $step_table=config('constants.STEP');
            $project_table=config('constants.PROJECT');
            $admin_table=config('constants.ADMIN');
            $customer_table=config('constants.CUSTOMER');
            $type_table=config('constants.TYPE');

            $arr_post_data=Input::get();
            $arr_var['arr_post_data']=$arr_post_data;
            foreach($arr_post_data as $k=>$v)
            {
                $arr_post_data[$k]=trim($v);
            }

            $arr_project_where=$this->arr_login_user['is_super']==1 ? [] : [[$customer_table.'.source','<>',4],[$customer_table.'.admin_id',$this->arr_login_user['id']]];//where条件
            if(isset($arr_post_data['c_id']))
            {
                $arr_project_where[]=[$project_table.'.customer_id','=',$arr_post_data['c_id']];
            }
            if(isset($arr_post_data['select_admin_name']) && $arr_post_data['select_admin_name']!='')
            {
                $arr_project_where[]=[$admin_table.'.name','=',$arr_post_data['select_admin_name']];
            }
            if(isset($arr_post_data['select_project_name']) && $arr_post_data['select_project_name']!='')
            {
                $arr_project_where[]=[$project_table.'.name','=',$arr_post_data['select_project_name']];
            }
            if(isset($arr_post_data['select_project_cycle']) && $arr_post_data['select_project_cycle']!='')
            {
                $arr_project_where[]=[$project_table.'.cycle','=',$arr_post_data['select_project_cycle']];
            }
            if(isset($arr_post_data['select_project_customer']) && $arr_post_data['select_project_customer']!='')
            {
                $this->select_phone=$arr_post_data['select_project_customer'];
            }

            if(isset($arr_post_data['select_create_time_start']) && $arr_post_data['select_create_time_start']!='')
            {
                if(!isset($arr_post_data['select_create_time_end']) || $arr_post_data['select_create_time_end']=='')
                {
                    $arr_project_where[]=[$project_table.'.create_time','>=',strtotime($arr_post_data['select_create_time_start'])];
                }
            }
            if(isset($arr_post_data['select_create_time_end']) && $arr_post_data['select_create_time_end']!='')
            {
                if(!isset($arr_post_data['select_create_time_start']) || $arr_post_data['select_create_time_start']=='')
                {
                    $arr_project_where[]=[$project_table.'.create_time','<=',strtotime($arr_post_data['select_create_time_end'])];
                }
            }
            if(isset($arr_post_data['select_create_time_start']) && $arr_post_data['select_create_time_start']!='' && isset($arr_post_data['select_create_time_end']) && $arr_post_data['select_create_time_end']!='')
            {
               $arr_project_where[]=[$project_table.'.create_time','>=',strtotime($arr_post_data['select_create_time_start'])];
               $arr_project_where[]=[$project_table.'.create_time','<=',strtotime($arr_post_data['select_create_time_end'])];
            }
            if(isset($arr_post_data['select_project_status']) && $arr_post_data['select_project_status']!=2)
            {
                $arr_project_where[]=[$project_table.'.status','=',$arr_post_data['select_project_status']];
            }
            if(isset($arr_post_data['select_project_step']) && $arr_post_data['select_project_step']!=0)
            {
                $arr_project_where[]=[$project_table.'.step_id','=',$arr_post_data['select_project_step']];
            }
            if(isset($arr_post_data['select_pay_status']) && $arr_post_data['select_pay_status']!=2)
            {
                $arr_project_where[]=[$project_table.'.payment_status','=',$arr_post_data['select_pay_status']];
            }
            if(isset($arr_post_data['select_project_is_bid']) && $arr_post_data['select_project_is_bid']!=2)
            {
                $arr_project_where[]=[$project_table.'.is_bid','=',$arr_post_data['select_project_is_bid']];
            }
            if(isset($arr_post_data['select_project_type']) && $arr_post_data['select_project_type']!=0)
            {
                $arr_project_where[]=[$project_table.'.type_id','=',$arr_post_data['select_project_type']];
            }
//            DB::connection()->enableQueryLog();
            if($this->select_phone!='')
            {
                $arr_data = DB::table($project_table)->join($admin_table,$admin_table.'.id','=',$project_table.'.admin_id')->join($step_table,$step_table.'.id','=',$project_table.'.step_id')->join($customer_table,$customer_table.'.id','=',$project_table.'.customer_id')->join($type_table,$type_table.'.id','=',$project_table.'.type_id')->join($admin_table.' as cus_admin','cus_admin.id','=',$customer_table.'.admin_id')->select('cus_admin.name as cus_admin_name',$project_table.'.*',$admin_table.'.name as admin_name',$step_table.'.name as step_name',$customer_table.'.phone as cus_phone',$customer_table.'.wechat as cus_wechat',$customer_table.'.landline as cus_landline',$customer_table.'.company as company_name',$customer_table.'.admin_id as cus_admin_id',$type_table.'.name as type_name')->where($arr_project_where)->where(function ($query) {
                    $query->where(config('constants.CUSTOMER') . '.phone', '=', $this->select_phone)->orwhere(config('constants.CUSTOMER') . '.landline', '=', $this->select_phone)->orwhere(config('constants.CUSTOMER') . '.wechat', '=', $this->select_phone);
                })->orderBy('id', 'desc')->get();
            }
            else
            {
                $arr_data = DB::table($project_table)->join($admin_table,$admin_table.'.id','=',$project_table.'.admin_id')->join($step_table,$step_table.'.id','=',$project_table.'.step_id')->join($customer_table,$customer_table.'.id','=',$project_table.'.customer_id')->join($type_table,$type_table.'.id','=',$project_table.'.type_id')->join($admin_table.' as cus_admin','cus_admin.id','=',$customer_table.'.admin_id')->select('cus_admin.name as cus_admin_name',$project_table.'.*',$admin_table.'.name as admin_name',$step_table.'.name as step_name',$customer_table.'.phone as cus_phone',$customer_table.'.wechat as cus_wechat',$customer_table.'.landline as cus_landline',$customer_table.'.company as company_name',$customer_table.'.admin_id as cus_admin_id',$type_table.'.name as type_name')->where($arr_project_where)->orderBy('id', 'desc')->get();
            }
//            echo '<pre>';
//            print_r(DB::getQueryLog());
            $arr_var['arr_step']=$this->step_list();
            $arr_var['arr_type']=$this->type_list();
            $arr_var['arr_project_admin']=DB::table($admin_table)->where('status',1)->get();
            $current_page = Input::get("page",1);

            $items = array_slice(json_decode(json_encode($arr_data),true),($current_page-1)*$this->pub_per_page,$this->pub_per_page);
            $arr_var['project_rows']=count($arr_data);
            $arr_var['arr_project'] = new LengthAwarePaginator($items, $arr_var['project_rows'],$this->pub_per_page);

            $arr_var['project_status']=$this->project_status;
            $arr_var['project_is_bid']=$this->project_is_bid;
            $arr_var['project_pay_status']=$this->project_pay_status;
//var_dump($arr_var);die;
            return view('Admin.project_index',$arr_var);
        }

        /**
         * 获取项目信息
         * @author tuomeikeji
         * @time 2019-04-18
         */
        public function get_project_detail()
        {
            $project_table=config('constants.PROJECT');
            $customer_table=config('constants.CUSTOMER');

            $project_id=trim(Input::get('project_id'));
            $arr_project_where=[[$project_table.'.id',$project_id]];
            $obj_project=DB::table($project_table)->join($customer_table,$customer_table.'.id','=',$project_table.'.customer_id')->select($project_table.'.*',$customer_table.'.phone as cus_phone',$customer_table.'.landline as cus_landline')->where($arr_project_where)->first();
//            var_dump(json_encode($obj_project));die;
            $obj_project->cus_company=$obj_project->cus_phone=='' ? $obj_project->cus_landline : $obj_project->cus_phone;
            return $obj_project==NULL ? 0 : json_encode($obj_project);
        }

        /**
         *更新项目信息
         * @author tuomeikeji
         * @time 2019-04-18
         */
        public function update_project()
        {
            $project_table=config('constants.PROJECT');
            $customer_table=config('constants.CUSTOMER');
            $role_table=config('constants.ROLE');
            $admin_role_table=config('constants.ADMIN_ROLE');
            $arr_update=self::pub_add_update();
            $project_id=$arr_update['project_id'];
            $obj_customer=DB::table($customer_table)->select('id','is_new_customer')->where([['phone',$arr_update['company']],['status',1]])->orWhere([['landline',$arr_update['company']],['status',1]])->first();//查询客户是否存在
            if($obj_customer==NULL)
            {
                $this->arr_return['message']='客户不存在';
                $this->arr_return['error_input']='modal_customer_company';
                return json_encode($this->arr_return);
            }

            $obj_project=DB::table($project_table)->select('id')->where([['name',$arr_update['name']],['id','<>',$project_id]])->first();//查询项目是否存在
            if($obj_project!=NULL)
            {
                $this->arr_return['message']='项目已存在';
                $this->arr_return['error_input']='modal_project_name';
                return json_encode($this->arr_return);
            }

            unset($arr_update['company']);
            $arr_update['customer_id']=$obj_customer->id;
            $arr_update['last_time'] = time();

            unset($arr_update['project_id']);

            if($obj_customer->is_new_customer==0 && $arr_update['payment_status']==1)//如果是新客户并且已付款改成老客户分配给售后主管
            {
                $obj_admin_zg=DB::table($role_table)->join($admin_role_table,$admin_role_table.'.role_id','=',$role_table.'.id')->select($admin_role_table.'.admin_id')->where($role_table.'.tab','shzg')->first();//售后主管信息
                if($obj_admin_zg==NULL)
                {
                    $this->arr_return['message'] = '请先去添加售后主管角色并分配主管';
                    return json_encode($this->arr_return);
                }

                DB::beginTransaction();
                DB::table($project_table)
                    ->where('id', $project_id)
                    ->update($arr_update);

//                var_dump($obj_customer);die;
                DB::table($customer_table)->where('id', $obj_customer->id)->update(['is_new_customer'=>1,'admin_id'=>$obj_admin_zg->admin_id]);//分配给售后主管
                DB::commit();
            }
            else
            {
                DB::table($project_table)
                    ->where('id', $project_id)
                    ->update($arr_update);
            }
//            DB::commit();
            $this->arr_return['status'] = 1;
            $this->arr_return['message'] = '修改成功';
            return json_encode($this->arr_return);
        }

        /**
         * 添加项目信息
         * @author tuomeikeji
         * @time 2019-04-18
         */
        public function add_project()
        {
            $project_table=config('constants.PROJECT');
            $customer_table=config('constants.CUSTOMER');
            $role_table=config('constants.ROLE');
            $admin_role_table=config('constants.ADMIN_ROLE');

            $arr_update=self::pub_add_update();
            $obj_customer=DB::table($customer_table)->select('id','is_new_customer')->where([['phone',$arr_update['company']],['status',1]])->orWhere([['landline',$arr_update['company']],['status',1]])->first();//查询客户是否存在
            if($obj_customer==NULL)
            {
                $this->arr_return['message']='客户不存在';
                $this->arr_return['error_input']='modal_customer_company';
                return json_encode($this->arr_return);
            }

            $obj_project=DB::table($project_table)->select('id')->where([['name',$arr_update['name']]])->first();//查询项目是否存在
            if($obj_project!=NULL)
            {
                $this->arr_return['message']='项目已存在';
                $this->arr_return['error_input']='modal_project_name';
                return json_encode($this->arr_return);
            }

            unset($arr_update['company']);
            $arr_update['customer_id']=$obj_customer->id;
            $arr_update['last_time'] = time();

            $arr_update['create_time'] = time();

            unset($arr_update['project_id']);

            if($obj_customer->is_new_customer==0 && $arr_update['payment_status']==1)//如果是新客户并且已付款改成老客户分配给售后主管
            {
                $obj_admin_zg=DB::table($role_table)->join($admin_role_table,$admin_role_table.'.role_id','=',$role_table.'.id')->select($admin_role_table.'.admin_id')->where($role_table.'.tab','shzg')->first();//售后主管信息
//                var_dump($obj_customer);die;
                if($obj_admin_zg==NULL)
                {
                    $this->arr_return['message'] = '请先去添加售后主管角色并分配主管';
                    return json_encode($this->arr_return);
                }
                DB::beginTransaction();
                DB::table($customer_table)->where('id', $obj_customer->id)->update(['is_new_customer'=>1,'admin_id'=>$obj_admin_zg->admin_id]);//分配给售后主管
                $insert_id=DB::table($project_table)->insertGetId($arr_update);
                if($insert_id>0)
                {
                    DB::commit();
                    $this->arr_return['status'] = 1;
                    $this->arr_return['message'] = '添加成功';
                    return json_encode($this->arr_return);
                }
                DB::rollBack();
                $this->arr_return['message'] = '添加失败';
                return json_encode($this->arr_return);
            }
            else
            {
                $insert_id=DB::table($project_table)->insertGetId($arr_update);
                if($insert_id>0)
                {
                    $this->arr_return['status'] = 1;
                    $this->arr_return['message'] = '添加成功';
                    return json_encode($this->arr_return);
                }

                $this->arr_return['message'] = '添加失败';
                return json_encode($this->arr_return);
            }
        }

        /**
         * 收集项目信息
         * @author tuomeikeji
         * @time 2019-04-18
         */

        private function pub_add_update()
        {
            $arr_update=Input::get();
            unset($arr_update['_token']);
            foreach($arr_update as $k=>$v)
            {
                $arr_update[$k]=trim(htmlspecialchars($v));
            }
            $arr_rule=[
                'company'=>['required'],
                'name'=>['required',new verfcount(100,'项目名字','modal_project_name')],
                'cycle'=>['required','numeric',new verfint(9999,'项目周期','modal_project_cycle')],
                'remarks'=>[new verfcount(300,'项目备注','modal_project_remarks')]
            ];

            $arr_message=[
                'company.*'=>['客户电话不能为空','modal_customer_company'],
                'name.*'=>['项目名称不能为空','modal_project_name'],
                'cycle.*'=>['项目周期填写错误','modal_project_cycle'],
            ];

            Validator::make($arr_update,$arr_rule,$arr_message)->validate();
            return $arr_update;
        }

        /**
         * 后台欢迎页面
         * @author tuomeikeji
         * @time 2019-04-18
         */
        public function welcome()
        {
            return view('Admin.welcome',['title'=>'欢迎页面']);
        }
    }