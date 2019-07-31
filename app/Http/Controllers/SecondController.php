<?php
    namespace App\Http\Controllers;
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\DB;

    class SecondController extends Controller
    {
        protected $arr_return = [
            'status' => 0,
            'message' => '错误',
            'error_input' => ''
        ];

        protected $pub_per_page=10;//每页显示数量
        protected $project_status=[//项目状态 注意 可以改名字 不要改id
            0=>'无效',
            1=>'有效'
        ];
        protected $project_pay_status=[//项目收款状态 注意 可以改名字 不要改id
            0=>'付款阶段',
            1=>'已收款'
        ];
        protected $project_is_bid=[//项目是否为投标项目 注意 可以改名字 不要改id
            0=>'否',
            1=>'是'
        ];
        protected $connection_records=[//收款记录状态 注意 可以改名字 不要改id
            0=>'未收款',
            1=>'已收款'
        ];
        protected $connection_records_name=[
            0=>'首款',
            1=>'中款',
            2=>'尾款',
            3=>'全款'
        ];
        protected $expenditure_status=[//财务支出状态 注意 可以改名字 不要改id
            0=>'未支付',
            1=>'已支付'
        ];
        protected $expenditure_name=[
            1=>'差旅费',
            2=>'办公费',
            3=>'交通费',
            4=>'通讯费',
            5=>'餐补',
            6=>'物业费',
            7=>'电费',
            8=>'房租',
            9=>'工资',
            10=>'其他',
        ];
        protected $pay_method=[//支付方式 注意 可以改名字 不要改id
            1=>'支付宝',
            2=>'微信',
            3=>'公户',
            4=>'工商银行',
            5=>'平安银行',
            6=>'招商银行',
            7=>'其他'
        ];
        protected $customer_source=[//客户来源 注意 可以改名字 不要改id
            0=>'其他',
            1=>'百度',
            2=>'360',
            3=>'老客户推荐',
//            4=>'招标',
            4=>'公司渠道(大客户)',
            5=>'公司渠道(普通客户)',
            6=>'淘宝'
        ];
        protected $contract_status=[//合同状态 注意 可以改名字 不要改id 
            0=>'已作废',
            1=>'跟踪中',
            2=>'已签约',
            3=>'派单中',
            4=>'开发中',
            5=>'收款期',
            9=>'已完结',
            10=>'转售后',
        ];
        protected $admin_status=[//管理员状态 注意 可以改名字 不要改id
            0=>'禁用',
            1=>'启用',
        ];
        protected $admin_sex=[
            0=>'男',
            1=>'女'
        ];
        protected $customer_status=[//客户状态 注意 可以改名字 不要改id
            0=>'无效',
            1=>'有效',
        ];
        protected $customer_type=[//客户类型 注意 可以改名字 不要改id
            0=>'个人',
            1=>'企业',
        ];
        protected $arr_new_customer=[//是否新客户 注意 可以改名字 不要改id
            0=>'否',
            1=>'是'
        ];
        protected $menu_status=[//导航菜单状态 注意 可以改名字 不要改id
            0=>'无效',
            1=>'有效'
        ];
        protected $role_status=[//角色状态 注意 可以改名字 不要改id
            0=>'无效',
            1=>'有效'
        ];
        protected $power_status=[//权限状态 注意 可以改名字 不要改id
            0=>'无效',
            1=>'有效'
        ];
        protected $step_status=[//项目进度状态 注意 可以改名字 不要改id
            0=>'无效',
            1=>'有效'
        ];
        protected $type_status=[//项目类型状态 注意 可以改名字 不要改id
            0=>'无效',
            1=>'有效'
        ];
        protected $wages_status=[//工资状态 注意 可以改名字 不要改id
            0=>'无效',
            1=>'有效'
        ];
        protected $arr_login_user=[];//登录管理员信息

        protected $power_special_case=['/','login_check','sign_out','modify_password','welcome'];//路由特例 不受权限影响
        protected $login_special_case=['/','login_check','sign_out','modify_password'];//路由特例 不受未登录影响

        public function __construct()
        {
            $this->middleware(function($request, $next)
            {
                $admin_table = config('constants.ADMIN');

                $curr_route_name= Route::currentRouteName();
                if(!session()->has('user_info') && !in_array($curr_route_name,$this->login_special_case))//未登录先去登陆
                {
                    return redirect('/')->send();
                }

                $this->arr_login_user=json_decode(json_encode(session('user_info')),true);//登录用户信息
                $obj_user=DB::table($admin_table)->where('id',$this->arr_login_user['id'])->select('status')->first();
                if($obj_user!=NULL && $obj_user->status==0 && !in_array($curr_route_name,$this->login_special_case))//管理员被禁用
                {
                    return redirect('/sign_out')->send();
                }

                $arr_have_power=[];
                if($this->arr_login_user['is_super']!=1)//超管和特例路由不受权限限制
                {
                    $obj_have_power=self::admin_power_list();//当前管理员的权限
                    foreach($obj_have_power as $v)
                    {
                        if(!in_array($v,$arr_have_power))
                        {
                            $arr_have_power[]=$v->link;
                        }
                    }
                    if(!in_array($curr_route_name,$arr_have_power) && !in_array($curr_route_name,$this->power_special_case))//权限判断
                    {
                        if(request()->ajax())
                        {
                            $this->arr_return['message']='您没有该权限';
                            $this->arr_return['error_input']='';
                            echo json_encode($this->arr_return);
                            die;
                        }
                        echo '您没有该权限';
                        die;
                    }
                }

                $arr_static = ['css' => [], 'js' => []];//要加载的资源数组
                $arr_public_static=config('js_css.public');//读取公共资源配置
                $arr_route_static=config('js_css.'.$curr_route_name)==NULL ? [] : config('js_css.'.$curr_route_name);//读取模块资源配置

                $arr_static['css'] = isset($arr_route_static['css']) ? array_merge($arr_public_static['css'], $arr_route_static['css']) : $arr_public_static['css'];//合并全部css资源
                $arr_static['js'] = isset($arr_route_static['js']) ? array_merge($arr_public_static['js'], $arr_route_static['js']) : $arr_public_static['js'];//合并全部js资源
//var_dump($arr_have_power);
                view()->share('arr_static', $arr_static);
                view()->share('arr_login_admin', $this->arr_login_user);
                view()->share('curr_route_name', $curr_route_name);
                view()->share('arr_have_power', $arr_have_power);
                view()->share('is_nav', 1);//是否显示导航 0不显示 1显示 默认显示
                $menu_table=config('constants.MENU');
                $menu_list_table=config('constants.MENU_LIST');
                $arr_menu_where=[[$menu_table.'.status',1],[$menu_list_table.'.status',1],[$menu_list_table.'.describe','admin']];
                $menu_list=DB::table($menu_table)->join($menu_list_table,$menu_list_table.'.id','=',$menu_table.'.type')->select($menu_table.'.*')->where($arr_menu_where)->orderBy('order','asc')->orderBy('id','asc')->get();//读取后台导航菜单
//                var_dump($menu_list);die;
                view()->share('menu_list', $menu_list);
                return $next($request);
            });
        }

        /**
         * 获取有效项目进度列表
         * @author tuomeikeji
         * @time 2019-04-17
         */
        protected function step_list()
        {
            $step_table=config('constants.STEP');
            $arr_step_where=[['status',1]];
            return DB::table($step_table)->where($arr_step_where)->get();
        }

        /**
         * 获取有效项目类型列表
         * @author tuomeikeji
         * @time 2019-04-17
         */
        protected function type_list()
        {
            $type_table=config('constants.TYPE');
            $arr_type_where=[['status',1]];
            return DB::table($type_table)->where($arr_type_where)->get();
        }

        /**
         * 获取有效权限列表
         * @author tuomeikeji
         * @time 2019-04-17
         */
        protected function get_power_list()
        {
            $power_table=config('constants.POWER');
            $arr_power_where=[['status',1]];//权限where条件
            $obj_power = DB::table($power_table)->where($arr_power_where)->orderBy('id', 'desc')->get();
            $arr_power=json_decode(json_encode(getTree($obj_power)),true);
            return $arr_power;
        }

        /**
         * 获取当前管理员有效权限列表
         * @author tuomeikeji
         * @time 2019-04-17
         */
        protected function admin_power_list()
        {
            $role_power_table=config('constants.ROLE_POWER');
            $admin_role_table=config('constants.ADMIN_ROLE');
            $power_table=config('constants.POWER');
            $role_table=config('constants.ROLE');

            $arr_where=[[$admin_role_table.'.admin_id',$this->arr_login_user['id']],[$power_table.'.status',1],[$role_table.'.status',1]];
            $obj_have_power=DB::table($admin_role_table)->join($role_power_table,$admin_role_table.'.role_id','=',$role_power_table.'.role_id')->where($arr_where)->join($power_table,$power_table.'.id','=',$role_power_table.'.power_id')->join($role_table,$role_table.'.id','=',$admin_role_table.'.role_id')->select($power_table.'.link')->get();
            return $obj_have_power;
        }

        /**
         * 获取当前管理员有效组
         * @author tuomeikeji
         * @time 2019-05-27
         */
        protected function admin_role_list()
        {
            $admin_role_table=config('constants.ADMIN_ROLE');
            $role_table=config('constants.ROLE');
            $arr_where=[[$admin_role_table.'.admin_id',$this->arr_login_user['id']]];
            $obj_have_role=DB::table($admin_role_table)->join($role_table,$role_table.'.id','=',$admin_role_table.'.role_id')->select($role_table.'.*')->where($arr_where)->get();
            return $obj_have_role;
        }

        /**
         * 获取某个组的有效成员
         * @author tuomeikeji
         * @time 2019-05-27
         */
        protected function role_admin_list($role_id=0)
        {
            $admin_role_table=config('constants.ADMIN_ROLE');
            $admin_table=config('constants.ADMIN');
            $arr_where=[[$admin_role_table.'.role_id',$role_id]];
            $obj_have_role=DB::table($admin_role_table)->join($admin_table,$admin_table.'.id','=',$admin_role_table.'.admin_id')->select($admin_table.'.id',$admin_table.'.name')->where($arr_where)->get();
            return $obj_have_role;
        }
    }
