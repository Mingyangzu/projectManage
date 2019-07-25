<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\SecondController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use App\Rules\verfcount;
class RoleController extends SecondController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 角色首页
     * @author tuomeikeji
     * @time 2019-04-19
     */
    public function role_index()
    {
        $arr_var=['title'=>'角色管理'];

        $role_table=config('constants.ROLE');

        $arr_post_data=Input::get();
        $arr_var['arr_post_data']=$arr_post_data;
        foreach($arr_post_data as $k=>$v)
        {
            $arr_post_data[$k]=trim($v);
        }

        $arr_role_where=[];//角色where条件

        if(isset($arr_post_data['select_role_name']) && $arr_post_data['select_role_name']!='')
        {
            $arr_role_where[]=[$role_table.'.name','=',$arr_post_data['select_role_name']];
        }
        if(isset($arr_post_data['select_role_status']) && $arr_post_data['select_role_status']!=2)
        {
            $arr_role_where[]=[$role_table.'.status','=',$arr_post_data['select_role_status']];
        }

        $current_page = Input::get("page",1);
        $arr_data = DB::table($role_table)->where($arr_role_where)->orderBy('id', 'desc')->get();
        $items = array_slice(json_decode(json_encode($arr_data),true),($current_page-1)*$this->pub_per_page,$this->pub_per_page);
        $arr_var['role_rows']=count($arr_data);
        $arr_var['arr_role'] = new LengthAwarePaginator($items, $arr_var['role_rows'],$this->pub_per_page);

        $arr_var['arr_power']=self::get_power_list();
        $arr_var['role_status']=$this->role_status;
        return view('Admin.role_index',$arr_var);
    }

    /**
     * 获取角色信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function get_role()
    {
        $role_table=config('constants.ROLE');

        $role_id=trim(Input::get('role_id'));
//        var_dump($role_id);die;
        $arr_role_where=[[$role_table.'.id',$role_id]];
        $obj_role=DB::table($role_table)->where($arr_role_where)->first();
//            var_dump(json_encode($obj_project));die;
        return $obj_role==NULL ? 0 : json_encode($obj_role);
    }

    /**
     * 更新角色信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function update_role()
    {
        $role_table=config('constants.ROLE');

        $arr_update=self::pub_add_update();

        $arr_role_where=[['name',$arr_update['name']], ['status',1],['id','<>',$arr_update['role_id']]];
        $obj_role=DB::table($role_table)->select('id')->where($arr_role_where)->first();//查询角色名字是否存在
        if($obj_role!=NULL)
        {
            $this->arr_return['message']='角色已名字存在';
            $this->arr_return['error_input']='modal_role_name';
            return json_encode($this->arr_return);
        }

        $arr_role_where=[['tab',$arr_update['tab']], ['status',1],['id','<>',$arr_update['role_id']]];
        $obj_role=DB::table($role_table)->select('id')->where($arr_role_where)->first();//查询角色标签是否存在
        if($obj_role!=NULL)
        {
            $this->arr_return['message']='角色标签已存在';
            $this->arr_return['error_input']='modal_role_tab';
            return json_encode($this->arr_return);
        }

        $arr_update['last_time'] = time();
        $role_id=$arr_update['role_id'];
        unset($arr_update['role_id']);
//var_dump($arr_update);die;
        DB::table($role_table)
            ->where('id', $role_id)
            ->update($arr_update);
        $this->arr_return['status'] = 1;
        $this->arr_return['message'] = '修改成功';
        return json_encode($this->arr_return);
    }

    /**
     * 添加角色信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function add_role()
    {
        $role_table=config('constants.ROLE');

        $arr_update=self::pub_add_update();

        $arr_role_where=[['name',$arr_update['name']],['status',1]];
        $obj_role=DB::table($role_table)->select('id')->where($arr_role_where)->first();//查询角色名字是否存在
        if($obj_role!=NULL)
        {
            $this->arr_return['message']='角色名字已存在';
            $this->arr_return['error_input']='modal_role_name';
            return json_encode($this->arr_return);
        }

        $arr_role_where=[['tab',$arr_update['tab']],['status',1]];
        $obj_role=DB::table($role_table)->select('id')->where($arr_role_where)->first();//查询角色标签是否存在
        if($obj_role!=NULL)
        {
            $this->arr_return['message']='角色标签已存在';
            $this->arr_return['error_input']='modal_role_tab';
            return json_encode($this->arr_return);
        }

        unset($arr_update['role_id']);
        $arr_update['create_time'] = time();
        $arr_update['last_time'] = time();
        $insert_id=DB::table($role_table)->insertGetId($arr_update);
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
     * 角色信息收集
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
            'name'=>['required',new verfcount(30,'角色名','modal_role_name')],
            'tab'=>['required',new verfcount(30,'角色标签','modal_role_tab')],
        ];
        $arr_message=[
            'name.*'=>['角色名不能为空','modal_role_name'],
            'tab.*'=>['角色标签不能为空','modal_role_tab'],
        ];
//var_dump($arr_update);
        Validator::make($arr_update,$arr_rule,$arr_message)->validate();

        return $arr_update;
    }

    /**
     * 获取角色的权限
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function get_role_power_list()
    {
        $role_power_table=config('constants.ROLE_POWER');
        $power_table=config('constants.POWER');
        $role_id=trim(Input::get('role_id'));
        $obj_role_power=DB::table($role_power_table)->join($power_table,$power_table.'.id','=',$role_power_table.'.power_id')->select($power_table.'.id',$power_table.'.name')->where($role_power_table.'.role_id',$role_id)->get();//查询角色下的权限

        $arr_role_power=[];
        foreach($obj_role_power as $v)
        {
            $arr_role_power[]=$v->id;
        }
//        var_dump($arr_role_power);die;
        return $obj_role_power==NULL ? 0 : json_encode($arr_role_power);
    }

    /**
     * 给角色分配权限
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function submit_role_power()
    {
        $role_power_table=config('constants.ROLE_POWER');
        $role_id=trim(Input::get('role_id'));
        $arr_power=Input::get('arr_power');

        DB::table($role_power_table)->where('role_id',$role_id)->delete();
        foreach($arr_power as $v)
        {
            $arr_data=['role_id'=>$role_id,'power_id'=>$v];
            DB::table($role_power_table)->insertGetId($arr_data);
        }
        $this->arr_return['status'] = 1;
        $this->arr_return['message'] = '分配成功';
        return json_encode($this->arr_return);

        $this->arr_return['message'] = '分配失败';
        return json_encode($this->arr_return);
    }
}