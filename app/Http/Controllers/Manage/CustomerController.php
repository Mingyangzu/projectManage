<?php
    namespace App\Http\Controllers\Manage;
    use App\Http\Controllers\SecondController;
    use Illuminate\Support\Facades\Input;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Pagination\LengthAwarePaginator;
    use Illuminate\Support\Facades\Validator;
    use App\Rules\verfphone;
    use App\Rules\verfcount;
    use App\Rules\verfmoney;
    use App\Rules\verfint;
    use App\Rules\verflandline;
    
    use Illuminate\Http\Request;
    use App\Model\Customer as CustomerModel;
    use App\Model\Projects as ProjectsModel;
    use App\Model\Contract as ContractModel;
    
    
    class CustomerController extends SecondController
    {
        private  $select_phone='';
        public  $returnMsg = ['code' => 200, 'data' => [], 'msg' => ''];
        
        public function __construct()
        {
            parent::__construct();
        }

        public function index(){
            $data['source'] = $this->customer_source;
            $data['type'] = $this->customer_type;
            return view('Manage.customer',['title' => '客户列表', 'data' => json_encode($data)]);
        }
        
        public function customerlist(Request $request){
//            DB::connection()->enableQueryLog();
            $page = $request->filled('page') <= 1 ? 0 : $request->page - 1;
            $limit = $request->filled('limit') ? 10 : $request->limit;
            $where = [];
            $request->filled('username') && $where[] = ['customer.username', 'like', '%'.$request->username.'%'];
            $request->filled('company') && $where[] = ['customer.company', 'like', '%'. $request->company .'%'];
            $request->filled('phone') && $where[] = ['customer.phone', 'like', '%'. $request->phone .'%'];
            $request->filled('source') && $where[] = ['customer.source', $request->source];
            
            $total = CustomerModel::where($where);
            $lists = CustomerModel::selectRaw('customer.*, count(project.customer_id) total')
                    ->leftJoin('project', 'customer.id', '=', 'project.customer_id')
                    ->where($where);
            
            if($request->filled('create_time')){
                $ctimearr = explode('@',$request->create_time);
                $lists = $lists->whereBetween('customer.create_time', [strtotime($ctimearr[0]), strtotime($ctimearr[1])] );
                $total = $total->whereBetween('customer.create_time', [strtotime($ctimearr[0]), strtotime($ctimearr[1])]);
            }
            
            $total = $total->count();
            $lists = $lists->groupBy('customer.id')->orderBy('customer.id', 'desc')->skip($page * $limit)->take($limit)->get();
//       dump(DB::getQueryLog());  
            $this->returnMsg['total'] = $total;
            $this->returnMsg['data'] = $lists;
            $this->returnMsg['msg'] = 'success';
            return json_encode($this->returnMsg);
        }
        
        // 添加客户
        public function addcustomer(Request $request){
            if(!$request->isMethod('post')){
                $this->returnMsg['code'] = 304;
                $this->returnMsg['msg'] = '请求方式有误!';
                return json_encode($this->returnMsg);
            }
            if(!$request->filled('username')){
                $this->returnMsg['code'] = 304;
                $this->returnMsg['msg'] = '客户名为必填项!';
                return json_encode($this->returnMsg);
            }
            if(!$request->filled('phone') && !$request->filled('wechat') && !$request->filled('landline')){
                $this->returnMsg['code'] = 304;
                $this->returnMsg['msg'] = '联系电话、座机、微信号至少一个必填!';
                return json_encode($this->returnMsg);
            }
            
            $savedata = [];
            $savedata['username'] = $request->username;
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
            
            if($request->filled('editid')){
                $savedata['last_time'] = time();
                $msg = CustomerModel::where('id', $request->editid)->update($savedata);
            }else{
                $savedata['admin_id'] = $this->arr_login_user['id'];
                $savedata['admin_name'] = $this->arr_login_user['name'];
                $savedata['create_time'] = time();
                $msg = CustomerModel::insert($savedata);
            }
            
            if($msg == true){
                $this->returnMsg['msg'] = '成功!';
            }else{
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
        public function getcustomer()
        {
            $customer_table=config('constants.CUSTOMER');

            $customer_id=trim(Input::get('customer_id'));
            $arr_customer_where=[[$customer_table.'.id',$customer_id]];
            $obj_customer=DB::table($customer_table)->where($arr_customer_where)->first();

//            $obj_customer->show_status=$this->customer_status[$obj_customer->status];
//            $obj_customer->show_source=$this->customer_source[$obj_customer->source];
//            $obj_customer->show_type=$this->customer_type[$obj_customer->type];

            if($obj_customer){
                $obj_customer->create_time = date('Y-m-d H:i:s', $obj_customer->create_time);
                $obj_customer->last_time = date('Y-m-d H:i:s', $obj_customer->last_time);
                $this->returnMsg['msg'] = '成功!';
                $this->returnMsg['data'] = $obj_customer;
            }else{
                $this->returnMsg['code'] = 500;
                $this->returnMsg['msg'] = '获取失败!';
            }
            
            return json_encode($this->returnMsg);
        }
        
        // 获取用户所属项目
        public function getcontract(Request $request){
            if(!$request->filled('customer_id')){
                $this->returnMsg['code'] = 304;
                $this->returnMsg['msg'] = '提交参数有误!';
                return json_encode($this->returnMsg);
            }
            
            $lists = ProjectsModel::selectRaw('project.id, project.name, project.status, project.admin_name, project.deliver_date, project.create_time, project.remarks, type.name type_id')
                    ->leftJoin('type', 'project.type_id', '=', 'type.id')
                    ->where('project.customer_id', $request->customer_id)
                    ->get();
            
            if($lists){
                foreach($lists as &$v){
                    $v->status = $this->contract_status[$v->status];
//                    $v->create_time = explode(' ', $v->create_time)[0];
                }
            }
            
            $this->returnMsg['data'] = $lists;
            $this->returnMsg['msg'] = 'success';
            return $this->returnMsg;
        }

        
        
        /**
         * 客户管理
         * @author tuomeikeji
         * @time 2019-04-16
         */
        public function customer_index()
        {
            $arr_var=['title'=>'客户管理'];

            $customer_table=config('constants.CUSTOMER');
            $admin_table=config('constants.ADMIN');
            $arr_post_data=Input::get();
            $arr_var['arr_post_data']=$arr_post_data;
            foreach($arr_post_data as $k=>$v)
            {
                $arr_post_data[$k]=trim($v);
            }

            $arr_customer_table_where=$this->arr_login_user['is_super']==1 ? [] : [[$customer_table.'.source','<>',4]];//where条件 超管才允许查看大客户

            if(isset($arr_post_data['select_customer_name']) && $arr_post_data['select_customer_name']!='')
            {
                $arr_customer_table_where[]=[$customer_table.'.username','=',$arr_post_data['select_customer_name']];
            }
            if(isset($arr_post_data['select_company_name']) && $arr_post_data['select_company_name']!='')
            {
                $arr_customer_table_where[]=[$customer_table.'.company','=',$arr_post_data['select_company_name']];
            }
            if(isset($arr_post_data['select_customer_phone']) && $arr_post_data['select_customer_phone']!='')
            {
                $this->select_phone=$arr_post_data['select_customer_phone'];
            }
            if(isset($arr_post_data['select_customer_status']) && $arr_post_data['select_customer_status']!=2)
            {
                $arr_customer_table_where[]=[$customer_table.'.status','=',$arr_post_data['select_customer_status']];
            }
            if(isset($arr_post_data['select_customer_source']) && $arr_post_data['select_customer_source']!=100)
            {
                $arr_customer_table_where[]=[$customer_table.'.source','=',$arr_post_data['select_customer_source']];
            }

            if($this->arr_login_user['is_super']!=1)
            {
                $arr_curr_role = self::admin_role_list();//获取当前管理员角色

                foreach ($arr_curr_role as $v) {
                    if ($v->tab == 'xs')//销售只可以看到自己的新客户
                    {
                        $arr_customer_table_where[] = [$customer_table . '.is_new_customer', '=', 0];
                        $arr_customer_table_where[] = [$customer_table . '.admin_id', '=', $this->arr_login_user['id']];
                    }

                    if ($v->tab == 'xszg')//销售主管可以看到所有的新客户
                    {
                        $arr_customer_table_where[] = [$customer_table . '.is_new_customer', '=', 0];
                    }

                    if ($v->tab == 'sh')//售后只可以看到自己的老客户
                    {
                        $arr_customer_table_where[] = [$customer_table . '.is_new_customer', '=', 1];
                        $arr_customer_table_where[] = [$customer_table . '.admin_id', '=', $this->arr_login_user['id']];
                    }

                    if ($v->tab == 'shzg')//售后主管可以看到所有的老客户
                    {
                        $arr_customer_table_where[] = [$customer_table . '.is_new_customer', '=', 1];
                    }
                }
            }
            $current_page = Input::get("page",1);

//            DB::connection()->enableQueryLog();
            if($this->select_phone!='')
            {
                $arr_data = DB::table($customer_table)->join($admin_table,$admin_table.'.id','=',$customer_table.'.admin_id')->select($admin_table.'.name as admin_name',$customer_table.'.*')->where($arr_customer_table_where)->where(function ($query) {
                    $query->where(config('constants.CUSTOMER') . '.phone', '=', $this->select_phone)->orwhere(config('constants.CUSTOMER') . '.landline', '=', $this->select_phone)->orwhere(config('constants.CUSTOMER') . '.wechat', '=', $this->select_phone);
                })->orderBy('id', 'desc')->get();
            }
            else
            {
                $arr_data = DB::table($customer_table)->join($admin_table,$admin_table.'.id','=',$customer_table.'.admin_id')->select($admin_table.'.name as admin_name',$customer_table.'.*')->where($arr_customer_table_where)->orderBy('id', 'desc')->get();
            }
//            echo '<pre>';
//            print_r(DB::getQueryLog());
            $items = array_slice(json_decode(json_encode($arr_data),true),($current_page-1)*$this->pub_per_page,$this->pub_per_page);
            $arr_var['customer_rows']=count($arr_data);
            $arr_var['arr_customer'] = new LengthAwarePaginator($items, $arr_var['customer_rows'],$this->pub_per_page);
            $arr_var['customer_source'] = $this->customer_source;
            $arr_var['arr_new_customer'] = $this->arr_new_customer;
            $arr_var['customer_status']=$this->customer_status;
            $arr_var['customer_type']=$this->customer_type;
//var_dump($arr_var);die;
            return view('Admin.customer_index',$arr_var);
        }

       
        /**
         *更新客户信息
         * @author tuomeikeji
         * @time 2019-04-18
         */
        public function update_customer()
        {
            $customer_table=config('constants.CUSTOMER');
            $arr_update=self::pub_add_update();
            $customer_id=trim(Input::get('customer_id'));
            if($arr_update['phone']!='')
            {
                $obj_customer=DB::table($customer_table)->select('id')->where([['phone',$arr_update['phone']],['status',1],['id','<>',$customer_id]])->first();//查询客户手机号是否存在
                if($obj_customer!=NULL)
                {
                    $this->arr_return['message']='客户手机号已存在';
                    $this->arr_return['error_input']='modal_customer_tel';
                    return json_encode($this->arr_return);
                }

            }

            if($arr_update['landline']!='')
            {
                $obj_customer=DB::table($customer_table)->select('id')->where([['landline',$arr_update['landline']],['status',1],['id','<>',$customer_id]])->first();//查询客户座机是否存在
                if($obj_customer!=NULL)
                {
                    $this->arr_return['message']='客户座机已存在';
                    $this->arr_return['error_input']='modal_customer_landline';
                    return json_encode($this->arr_return);
                }

            }

            $arr_update['last_time'] = time();

            unset($arr_update['customer_id']);
            DB::table($customer_table)
                ->where('id', $customer_id)
                ->update($arr_update);

            $this->arr_return['status'] = 1;
            $this->arr_return['message'] = '修改成功';
            return json_encode($this->arr_return);
        }

        /**
         * 添加客户信息
         * @author tuomeikeji
         * @time 2019-04-18
         */
        public function add_customer()
        {
            $customer_table=config('constants.CUSTOMER');
            $arr_update=self::pub_add_update();
            if($arr_update['phone']!='')
            {
                $obj_customer=DB::table($customer_table)->select('id')->where([['phone',$arr_update['phone']],['status',1]])->first();//查询客户手机号是否存在
                if($obj_customer!=NULL)
                {
                    $this->arr_return['message']='客户手机号已存在';
                    $this->arr_return['error_input']='modal_customer_tel';
                    return json_encode($this->arr_return);
                }
            }

            if($arr_update['landline']!='')
            {
                $obj_customer=DB::table($customer_table)->select('id')->where([['landline',$arr_update['landline']],['status',1]])->first();//查询客户座机是否存在
                if($obj_customer!=NULL)
                {
                    $this->arr_return['message']='客户座机已存在';
                    $this->arr_return['error_input']='modal_customer_landline';
                    return json_encode($this->arr_return);
                }
            }
            unset($arr_update['customer_id']);
            $arr_update['last_time'] = time();
            $arr_update['create_time'] = time();
//            var_dump($arr_update);die;
            $insert_id=DB::table($customer_table)->insertGetId($arr_update);
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
         * 收集客户信息
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
//var_dump($arr_update);die;
            $arr_update['scale']=$arr_update['scale']=='' ? 0 : $arr_update['scale'];//规模不填默认为0
            $arr_rule=[
                'company'=>[new verfcount(100,'公司名字','modal_company_names')],
                'username'=>['required',new verfcount(100,'客户名字','modal_customer_names')],
                'phone'=>[new verfphone()],
                'landline'=>[new verflandline()],
                'wechat'=>[new verfcount(100,'微信','modal_customer_wechat')],
                'position'=>[new verfcount(30,'职位','modal_customer_position')],
                'turnover'=>['numeric',new verfmoney('modal_customer_turnover')],
                'address'=>[new verfcount(200,'公司地址','modal_customer_address')],
                'industry'=>[new verfcount(10,'所在行业','modal_customer_industry')],
                'main_business'=>[new verfcount(500,'主营业务','modal_customer_main_business')],
                'scale'=>'numeric',new verfint(4294967295,'公司规模','modal_customer_scale'),
                'remarks'=>[new verfcount(300,'备注','modal_customer_remarks')],
            ];

            if($arr_update['phone']=='' && $arr_update['landline']=='' && $arr_update['wechat']=='')//个人需要填
            {
                $arr_rule['phone'][]='required';
                $arr_rule['landline'][]='required';
                $arr_rule['wechat'][]='required';
            }

            if($arr_update['type']==1)//企业需要填
            {
                $arr_rule['main_business'][]='required';
                $arr_rule['company'][]='required';
            }
//var_dump($arr_rule['position'][1]);die;
            $arr_message=[
                'username.*'=>['客户名称不能为空','modal_customer_names'],
                'company.*'=>['公司名称不能为空','modal_company_names'],
                'phone.*'=>['客户手机号和座机以及微信必填一个','modal_customer_tel'],
                'landline.*'=>['客户手机号和座机以及微信必填一个','modal_customer_landline'],
                'wechat.*'=>['客户手机号和座机以及微信必填一个','modal_customer_wechat'],
                'position.*'=>['职位不能为空','modal_customer_position'],
                'turnover.*'=>['年营业额不能为空并且必须为数字','modal_customer_turnover'],
                'address.*'=>['公司地址不能为空','modal_customer_address'],
                'industry.*'=>['所在行业不能为空','modal_customer_industry'],
                'main_business.*'=>['主营业务不能为空','modal_customer_main_business'],
                'scale.*'=>['公司规模必须为数字','modal_customer_scale'],
            ];

            $arr_update['admin_id']=$this->arr_login_user['id'];
            Validator::make($arr_update,$arr_rule,$arr_message)->validate();
            return $arr_update;
        }

        /**
         * 获取要给客户分配的业务员
         * @author tuomeikeji
         * @time 2019-05-28
         */
        public function get_customer_admin()
        {
            $arr_post_data=Input::get();
            $arr_var['arr_post_data']=$arr_post_data;
            foreach($arr_post_data as $k=>$v)
            {
                $arr_post_data[$k]=trim($v);
            }

            $admin_role_table=config('constants.ADMIN_ROLE');
            $role_table=config('constants.ROLE');
            $admin_table=config('constants.ADMIN');
            if($arr_post_data['is_new']==0)//新客户 读取销售和销售主管列表
            {
                $arr_where=[[$role_table.'.status',1],[$admin_table.'.status',1]];
                $obj_appoint_list=DB::table($role_table)->select($admin_table.'.id',$admin_table.'.name')->join($admin_role_table,$admin_role_table.'.role_id','=',$role_table.'.id')->join($admin_table,$admin_table.'.id','=',$admin_role_table.'.admin_id')->where($arr_where)->where(function($query){
                    $query->where(config('constants.ROLE').'.tab','xs')->orwhere(config('constants.ROLE').'.tab','xszg');
                })->get();
            }

            if($arr_post_data['is_new']==1)//老客户 读取售后和售后主管列表
            {
                $arr_where=[[$role_table.'.status',1],[$admin_table.'.status',1]];
                $obj_appoint_list=DB::table($role_table)->select($admin_table.'.id',$admin_table.'.name')->join($admin_role_table,$admin_role_table.'.role_id','=',$role_table.'.id')->join($admin_table,$admin_table.'.id','=',$admin_role_table.'.admin_id')->where($arr_where)->where(function($query){
                    $query->where(config('constants.ROLE').'.tab','sh')->orwhere(config('constants.ROLE').'.tab','shzg');
                })->get();
            }
//var_dump($obj_appoint_list);die;
            return json_encode($obj_appoint_list);
        }

        /**
         * 分配业务员
         * @author tuomeikeji
         * @time 2019-05-28
         */
        public function appoint_customer_admin()
        {
            $arr_post_data=Input::get();
            $arr_var['arr_post_data']=$arr_post_data;
            foreach($arr_post_data as $k=>$v)
            {
                $arr_post_data[$k]=trim($v);
            }
            $customer_table=config('constants.CUSTOMER');
            $cus_id=$arr_post_data['cus_id'];
            unset($arr_post_data['cus_id']);
            unset($arr_post_data['_token']);

            DB::table($customer_table)->where('id',$cus_id)->update($arr_post_data);
            $this->arr_return['status'] = 1;
            $this->arr_return['message'] = '分配成功';
            return json_encode($this->arr_return);
        }
    }