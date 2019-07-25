<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\SecondController;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use App\Rules\verfcount;
use App\Rules\verfphone;
use App\Rules\verfid_card;
use App\Rules\verfbank_card;
class OperaterController extends SecondController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 管理员首页
     * @author tuomeikeji
     * @time 2019-04-19
     */
    public function admin_index()
    {
        $arr_var=['title'=>'管理员管理'];
        $admin_table=config('constants.ADMIN');
        $role_table=config('constants.ROLE');
        $admin_role_table=config('constants.ADMIN_ROLE');

        $arr_post_data=Input::get();

        $arr_var['arr_post_data']=$arr_post_data;
        foreach($arr_post_data as $k=>$v)
        {
            $arr_post_data[$k]=trim($v);
        }

        $arr_admin_where=[['is_super',0]];//where条件

        if(isset($arr_post_data['select_admin_name']) && $arr_post_data['select_admin_name']!='')
        {
            $arr_admin_where[]=[$admin_table.'.name','=',$arr_post_data['select_admin_name']];
        }
        if(isset($arr_post_data['select_admin_email']) && $arr_post_data['select_admin_email']!='')
        {
            $arr_admin_where[]=[$admin_table.'.email','=',$arr_post_data['select_admin_email']];
        }
        if(isset($arr_post_data['select_admin_status']) && $arr_post_data['select_admin_status']!=2)
        {
            $arr_admin_where[]=[$admin_table.'.status','=',$arr_post_data['select_admin_status']];
        }
        if(isset($arr_post_data['select_admin_sex']) && $arr_post_data['select_admin_sex']!=2)
        {
            $arr_admin_where[]=[$admin_table.'.sex','=',$arr_post_data['select_admin_sex']];
        }

        $current_page = Input::get("page",1);
        $arr_data = DB::table($admin_table)->where($arr_admin_where)->orderBy('id', 'desc')->get();
        foreach($arr_data as $k=>$v)
        {
            $str_role='';
            $obj_admin_role=DB::table($admin_role_table)->join($role_table,$admin_role_table.'.role_id','=',$role_table.'.id')->where($admin_role_table.'.admin_id',$v->id)->select($role_table.'.name')->get();
            foreach ($obj_admin_role as $vv)
            {
                $str_role.=$vv->name.',';
            }
            $arr_data[$k]->role=trim($str_role,',');
        }
        $items = array_slice(json_decode(json_encode($arr_data),true),($current_page-1)*$this->pub_per_page,$this->pub_per_page);
        $obj_role=DB::table($role_table)->where('status',1)->orderBy('id', 'desc')->get();//查询所有有效角色
//var_dump($obj_role);

        $arr_var['obj_role']=$obj_role;
        $arr_var['admin_rows']=count($arr_data);
        $arr_var['arr_admin'] = new LengthAwarePaginator($items, $arr_var['admin_rows'],$this->pub_per_page);

        $arr_var['admin_status']=$this->admin_status;
        $arr_var['admin_sex']=$this->admin_sex;
//var_dump($arr_var);die;
        return view('Admin.admin_index',$arr_var);
    }

    /**
     * 获取管理员信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function get_admin()
    {
        $admin_table=config('constants.ADMIN');
        $admin_role_table=config('constants.ADMIN_ROLE');

        $admin_id=trim(Input::get('admin_id'));
        $arr_admin_where=[[$admin_table.'.id',$admin_id]];
        $obj_admin=DB::table($admin_table)->where($arr_admin_where)->first();
//            var_dump(json_encode($obj_admin));die;

        $arr_role=[];
        $obj_admin_role=DB::table($admin_role_table)->where($admin_role_table.'.admin_id',$obj_admin->id)->get();
        foreach ($obj_admin_role as $v)
        {
            $arr_role[]=$v->role_id;
        }
        $obj_admin->role=$arr_role;

//        var_dump($obj_admin);die;
        return $obj_admin==NULL ? 0 : json_encode($obj_admin);
    }

    /**
     * 更新管理员信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function update_admin()
    {
        $admin_table=config('constants.ADMIN');
        $admin_role_table=config('constants.ADMIN_ROLE');
        $role_table=config('constants.ROLE');

        $arr_update=self::pub_add_update();

        $arr_admin_where=[['name',$arr_update['name']],['status',1],['id','<>',$arr_update['admin_id']]];
        $obj_project=DB::table($admin_table)->select('id')->where($arr_admin_where)->first();//查询管理员是否存在
        if($obj_project!=NULL)
        {
            $this->arr_return['message']='管理员名已存在';
            $this->arr_return['error_input']='modal_admin_name';
            return json_encode($this->arr_return);
        }

        $arr_admin_where=[['email',$arr_update['email']],['status',1],['id','<>',$arr_update['admin_id']]];
        $obj_project=DB::table($admin_table)->select('id')->where($arr_admin_where)->first();//查询邮箱是否存在
        if($obj_project!=NULL)
        {
            $this->arr_return['message']='邮箱已存在';
            $this->arr_return['error_input']='modal_admin_email';
            return json_encode($this->arr_return);
        }
        $arr_admin_where=[['phone',$arr_update['phone']],['status',1],['id','<>',$arr_update['admin_id']]];
        $obj_project=DB::table($admin_table)->select('id')->where($arr_admin_where)->first();//查询手机号是否存在
        if($obj_project!=NULL)
        {
            $this->arr_return['message']='手机号已存在';
            $this->arr_return['error_input']='modal_admin_phone';
            return json_encode($this->arr_return);
        }
        $arr_admin_where=[['bank_card',$arr_update['bank_card']],['status',1],['id','<>',$arr_update['admin_id']]];
        $obj_project=DB::table($admin_table)->select('id')->where($arr_admin_where)->first();//查询银行卡是否存在
        if($obj_project!=NULL)
        {
            $this->arr_return['message']='银行卡已存在';
            $this->arr_return['error_input']='modal_admin_bank_card';
            return json_encode($this->arr_return);
        }

        $arr_admin_where=[['id_card',$arr_update['id_card']],['status',1],['id','<>',$arr_update['admin_id']]];
        $obj_project=DB::table($admin_table)->select('id')->where($arr_admin_where)->first();//查询身份证是否存在
        if($obj_project!=NULL)
        {
            $this->arr_return['message']='身份证已存在';
            $this->arr_return['error_input']='modal_admin_id_card';
            return json_encode($this->arr_return);
        }

        $i=0;
        foreach($arr_update['role'] as $v)
        {
            $obj_role=DB::table($role_table)->select('tab')->where('id',$v)->first();
            if(in_array($obj_role->tab,['sh','shzg','xs','xszg']))
            {
                $i++;
            }
            if($i>1)
            {
                $this->arr_return['message']='管理员不能同时是销售 销售主管 售后 售后主管中的两种角色';
                return json_encode($this->arr_return);
            }

            if(in_array($obj_role->tab,['xszg','shzg']))
            {
                DB::table($admin_role_table)->where('role_id', $v)->delete();
            }
        }

        $arr_update['last_time'] = time();
        $admin_id=$arr_update['admin_id'];
        $arr_role=$arr_update['role'];
        unset($arr_update['admin_id']);
        unset($arr_update['role']);
//var_dump($arr_update);die;
        DB::beginTransaction();
        DB::table($admin_table)
            ->where('id', $admin_id)
            ->update($arr_update);

        DB::table($admin_role_table)->where('admin_id', $admin_id)->delete();
        $i=0;
        foreach($arr_role as $v)
        {
            $arr_role_admin['role_id']=$v;
            $arr_role_admin['admin_id']=$admin_id;
            $admin_role_id=DB::table($admin_role_table)->insertGetId($arr_role_admin);
            if($admin_role_id>0)
            {
                $i++;
            }
        }
        if($i == count($arr_role))
        {
            DB::commit();
            $this->arr_return['status'] = 1;
            $this->arr_return['message'] = '修改成功';
            return json_encode($this->arr_return);
        }
        DB::rollBack();
        $this->arr_return['message'] = '修改失败';
        return json_encode($this->arr_return);
    }

    /**
     * 添加管理员信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function add_admin()
    {
        $admin_table=config('constants.ADMIN');
        $admin_role_table=config('constants.ADMIN_ROLE');
        $role_table=config('constants.ROLE');

        $arr_update=self::pub_add_update();

        $arr_admin_where=[['name',$arr_update['name']],['status',1]];
        $obj_project=DB::table($admin_table)->select('id')->where($arr_admin_where)->first();//查询管理员是否存在
        if($obj_project!=NULL)
        {
            $this->arr_return['message']='管理员名已存在';
            $this->arr_return['error_input']='modal_admin_name';
            return json_encode($this->arr_return);
        }

        $arr_admin_where=[['email',$arr_update['email']],['status',1]];
        $obj_project=DB::table($admin_table)->select('id')->where($arr_admin_where)->first();//查询邮箱是否存在
        if($obj_project!=NULL)
        {
            $this->arr_return['message']='邮箱已存在';
            $this->arr_return['error_input']='modal_admin_email';
            return json_encode($this->arr_return);
        }

        $arr_admin_where=[['phone',$arr_update['phone']],['status',1]];
        $obj_project=DB::table($admin_table)->select('id')->where($arr_admin_where)->first();//查询手机号是否存在
        if($obj_project!=NULL)
        {
            $this->arr_return['message']='手机号已存在';
            $this->arr_return['error_input']='modal_admin_phone';
            return json_encode($this->arr_return);
        }

        $arr_admin_where=[['bank_card',$arr_update['bank_card']],['status',1]];
        $obj_project=DB::table($admin_table)->select('id')->where($arr_admin_where)->first();//查询银行卡是否存在
        if($obj_project!=NULL)
        {
            $this->arr_return['message']='银行卡已存在';
            $this->arr_return['error_input']='modal_admin_bank_card';
            return json_encode($this->arr_return);
        }

        $arr_admin_where=[['id_card',$arr_update['id_card']],['status',1]];
        $obj_project=DB::table($admin_table)->select('id')->where($arr_admin_where)->first();//查询身份证是否存在
        if($obj_project!=NULL)
        {
            $this->arr_return['message']='身份证已存在';
            $this->arr_return['error_input']='modal_admin_id_card';
            return json_encode($this->arr_return);
        }

        $i=0;
        foreach($arr_update['role'] as $v)
        {
            $obj_role=DB::table($role_table)->select('tab')->where('id',$v)->first();
            if(in_array($obj_role->tab,['sh','shzg','xs','xszg']))
            {
                $i++;
            }
            if($i>1)
            {
                $this->arr_return['message']='管理员不能同时是销售 销售主管 售后 售后主管中的两种角色';
                return json_encode($this->arr_return);
            }
        }

        $arr_role=$arr_update['role'];
        unset($arr_update['admin_id']);
        unset($arr_update['role']);
        $arr_update['create_time'] = time();
        $arr_update['last_time'] = time();
        $arr_update['salt']=str_random(6);
        $arr_update['password']=create_password('123456',$arr_update['salt']);
        DB::beginTransaction();
        $admin_id=DB::table($admin_table)->insertGetId($arr_update);
        if($admin_id>0)
        {
            $i=0;
            foreach($arr_role as $v)
            {
                $arr_role_admin['role_id']=$v;
                $arr_role_admin['admin_id']=$admin_id;
                $admin_role_id=DB::table($admin_role_table)->insertGetId($arr_role_admin);
                if($admin_role_id>0)
                {
                    $i++;
                }
            }
            if($i == count($arr_role))
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
        DB::rollBack();
        $this->arr_return['message'] = '添加失败';
        return json_encode($this->arr_return);
    }

    /**
     * 管理员信息收集
     * @author tuomeikeji
     * @time 2019-04-18
     */
    private function pub_add_update()
    {
        $arr_update=Input::get();
        unset($arr_update['_token']);
        foreach($arr_update as $k=>$v)
        {
            if(!is_array($v))
            {
                $arr_update[$k]=trim(htmlspecialchars($v));
            }
        }
        $arr_rule=[
            'name'=>['required',new verfcount(100,'管理员名字','modal_admin_name')],
            'email'=>['required','email'],
            'role'=>['required'],
            'phone'=>['required',new verfphone('modal_admin_phone')],
            'id_card'=>['required',new verfid_card('modal_admin_id_card')],
            'bank_card'=>['required',new verfbank_card('modal_admin_bank_card')],
        ];
        $arr_message=[
            'name.*'=>['管理员名不能为空','modal_admin_name'],
            'email.required'=>['邮箱名字不能为空','modal_admin_email'],
            'email.email'=>['邮箱格式不正确','modal_admin_email'],
            'role.*'=>['角色必选','modal_admin_role'],
            'phone.*'=>['手机不能为空','modal_admin_phone'],
            'id_card.*'=>['身份证不能为空','modal_admin_id_card'],
            'bank_card.*'=>['银行卡不能为空','modal_admin_bank_card'],
        ];
        Validator::make($arr_update,$arr_rule,$arr_message)->validate();

        $arr_update['bank_name']=bank_name($arr_update['bank_card']);
        return $arr_update;
    }
}