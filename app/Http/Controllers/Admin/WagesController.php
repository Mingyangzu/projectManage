<?php
    namespace App\Http\Controllers\Admin;
    use App\Http\Controllers\SecondController;
    use Illuminate\Support\Facades\Input;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Pagination\LengthAwarePaginator;
    use Illuminate\Support\Facades\Validator;
    use App\Rules\verfcount;
    use App\Rules\verfmoney;
    class WagesController extends SecondController
    {
        private  $select_phone='';
        public function __construct()
        {
            parent::__construct();
        }

        /**
         * 薪资管理
         * @author tuomeikeji
         * @time 2019-05-30
         */
        public function wages_index()
        {
            $arr_var=['title'=>'薪资管理'];

            $wages_table=config('constants.WAGES');
            $admin_table=config('constants.ADMIN');
            $arr_post_data=Input::get();
            $arr_var['arr_post_data']=$arr_post_data;
            foreach($arr_post_data as $k=>$v)
            {
                $arr_post_data[$k]=trim($v);
            }

            $arr_wages_table_where=[];
            $arr_admin_where=[['status',1]];//展示搜索员工列表
            if($this->arr_login_user['is_super']!=1)//超管可以查看所有人薪资
            {
                $i=0;
                $arr_curr_role = self::admin_role_list();
                foreach($arr_curr_role as $v)
                {
                    if($v->tab=='cw')//财务可以查看所有人薪资
                    {
                        $i++;
                    }
                }
                if($i==0)//不是超管和财务只可以查看自己的薪资并且只能搜索自己的薪资
                {
                    $arr_wages_table_where=[[$wages_table.'.admin_id',$this->arr_login_user['id']]];
                    $arr_admin_where[]=['id',$this->arr_login_user['id']];
                }
            }
            if(isset($arr_post_data['select_user_name']) && $arr_post_data['select_user_name']!=0)
            {
                $arr_wages_table_where[]=[$wages_table.'.admin_id','=',$arr_post_data['select_user_name']];
            }
            if(isset($arr_post_data['select_wages_status']) && $arr_post_data['select_wages_status']!=2)
            {
                $arr_wages_table_where[]=[$wages_table.'.status','=',$arr_post_data['select_wages_status']];
            }
            if(isset($arr_post_data['select_wages_time']) && $arr_post_data['select_wages_time']!='')
            {
                $arr_wages_table_where[]=[$wages_table.'.time','=',strtotime($arr_post_data['select_wages_time'])];
            }

            $obj_data=DB::table($wages_table)->join($admin_table.' as a_t','a_t.id','=',$wages_table.'.admin_id')->join($admin_table.' as o_t','o_t.id','=',$wages_table.'.last_operator')->select($wages_table.'.*','a_t.name as admin_name','o_t.name as operator_name')->where($arr_wages_table_where)->get();
            foreach($obj_data as $k=>$v)
            {
                $obj_data[$k]->payroll=round($v->basic+$v->post+$v->full+$v->education+$v->years+$v->oper_allowance+$v->skill+$v->payable_merit+$v->royalty+$v->perquisites+$v->bonus,2);//应发薪资
                $obj_data[$k]->deduct_salary=round($v->social_security+$v->deduct_merit+$v->leave+$v->late,2);//薪资扣除
                $obj_data[$k]->actual_salary=round($obj_data[$k]->payroll-$obj_data[$k]->deduct_salary,2);//实发薪资
            }
            $current_page = Input::get("page",1);
            $items = array_slice(json_decode(json_encode($obj_data),true),($current_page-1)*$this->pub_per_page,$this->pub_per_page);
            $arr_var['wages_rows']=count($obj_data);
            $arr_var['arr_wages'] = new LengthAwarePaginator($items, $arr_var['wages_rows'],$this->pub_per_page);
            $arr_var['wages_status']=$this->wages_status;
            $arr_var['admin_list']=DB::table($admin_table)->select('id','name')->where($arr_admin_where)->get();
            return view('Admin.wages_index',$arr_var);
        }

        /**
         * 获取薪资信息
         * @author tuomeikeji
         * @time 2019-05-30
         */
        public function get_wages()
        {
            $wages_table=config('constants.WAGES');
            $wages_id=trim(Input::get('wages_id'));
            $arr_wages_table_where=[[$wages_table.'.id',$wages_id]];
            $obj_wages=DB::table($wages_table)->where($arr_wages_table_where)->first();
            $obj_wages->time=$obj_wages->time==0 ? '' : date('Y-m',$obj_wages->time);
//            var_dump(json_encode($obj_project));die;
            return $obj_wages==NULL ? 0 : json_encode($obj_wages);
        }

        /**
         * 更新薪资信息
         * @author tuomeikeji
         * @time 2019-05-30
         */
        public function update_wages()
        {
            $wages_table=config('constants.WAGES');
            $arr_update=self::pub_add_update();
            $wages_id=trim(Input::get('wages_id'));

            $arr_wages_table_where=[[$wages_table.'.id','<>',$wages_id],[$wages_table.'.status',1],[$wages_table.'.time',$arr_update['time']],[$wages_table.'.admin_id',$arr_update['admin_id']]];
            $obj_wages=DB::table($wages_table)->where($arr_wages_table_where)->first();
            if($obj_wages!=NULL)
            {
                $this->arr_return['message'] = '该员工该月份的薪资已存在';
                return json_encode($this->arr_return);
            }

            $arr_update['last_time'] = time();

            unset($arr_update['wages_id']);
            DB::table($wages_table)
                ->where('id', $wages_id)
                ->update($arr_update);

            $this->arr_return['status'] = 1;
            $this->arr_return['message'] = '修改成功';
            return json_encode($this->arr_return);
        }

        /**
         * 添加薪资信息
         * @author tuomeikeji
         * @time 2019-05-30
         */
        public function add_wages()
        {
            $wages_table=config('constants.WAGES');
            $arr_insert=self::pub_add_update();

            $arr_wages_table_where=[[$wages_table.'.status',1],[$wages_table.'.time',$arr_insert['time']],[$wages_table.'.admin_id',$arr_insert['admin_id']]];
            $obj_wages=DB::table($wages_table)->where($arr_wages_table_where)->first();
            if($obj_wages!=NULL)
            {
                $this->arr_return['message'] = '该员工该月份的薪资已存在';
                return json_encode($this->arr_return);
            }

            unset($arr_insert['wages_id']);
            $arr_insert['last_time'] = time();
            $arr_insert['create_time'] = time();
//            var_dump($arr_update);die;
            $insert_id=DB::table($wages_table)->insertGetId($arr_insert);
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
         * 收集薪资信息
         * @author tuomeikeji
         * @time 2019-05-30
         */

        private function pub_add_update()
        {
            $arr_update=Input::get();
            unset($arr_update['_token']);

            $arr_rule=[];
            $arr_message=[];
            foreach($arr_update as $k=>$v)
            {
                $arr_update[$k]=trim($v);
                if(!in_array($k,['status','remarks','admin_id','time']))
                {
                    $arr_rule[$k]=['required','numeric',new verfmoney(99999999.99,'modal_wages_'.$k)];
                    $arr_message[$k.'.*']=['该金额不能为空且必须为数字','modal_wages_'.$k];
                }
            }
            $arr_rule['remarks']=new verfcount(500,'备注','modal_wages_remarks');
            $arr_rule['time']='required';
            $arr_message['remarks.*']=['备注不能为空','modal_wages_remarks'];
            $arr_message['time.*']=['时间不能为空','modal_wages_time'];
//print_r($arr_rule);die;
            Validator::make($arr_update,$arr_rule,$arr_message)->validate();
            $arr_update['time']=strtotime($arr_update['time']);
            $arr_update['last_operator']=$this->arr_login_user['id'];
            return $arr_update;
        }
    }