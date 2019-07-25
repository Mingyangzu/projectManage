<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\SecondController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use App\Rules\verfcount;
class PowerController extends SecondController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 权限首页
     * @author tuomeikeji
     * @time 2019-04-19
     */
    public function power_index()
    {
        $arr_var=['title'=>'权限管理'];
        $power_table=config('constants.POWER');

        $arr_post_data=Input::get();

        $arr_var['arr_post_data']=$arr_post_data;
        foreach($arr_post_data as $k=>$v)
        {
            $arr_post_data[$k]=trim($v);
        }

        $arr_power_where=[];//where条件

        if(isset($arr_post_data['select_power_name']) && $arr_post_data['select_power_name']!='')
        {
            $arr_power_where[]=[$power_table.'.name','=',$arr_post_data['select_power_name']];
        }
        if(isset($arr_post_data['select_power_status']) && $arr_post_data['select_power_status']!=2)
        {
            $arr_power_where[]=[$power_table.'.status','=',$arr_post_data['select_power_status']];
        }

        $current_page = Input::get("page",1);
        $arr_data = DB::table($power_table)->where($arr_power_where)->orderBy('id', 'desc')->get();
        $items = array_slice(json_decode(json_encode($arr_data),true),($current_page-1)*$this->pub_per_page,$this->pub_per_page);
        $arr_var['power_rows']=count($arr_data);
        $arr_var['arr_power'] = new LengthAwarePaginator($items, $arr_var['power_rows'],$this->pub_per_page);

        $arr_var['arr_power_list']=self::get_power_list();
        $arr_var['power_status']=$this->power_status;
        return view('Admin.power_index',$arr_var);
    }

    /**
     * 获取权限信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function get_power()
    {
        $power_table=config('constants.POWER');

        $power_id=trim(Input::get('power_id'));
//        var_dump($role_id);die;
        $arr_power_where=[[$power_table.'.id',$power_id]];
        $obj_power=DB::table($power_table)->where($arr_power_where)->first();
//            var_dump(json_encode($obj_project));die;
        return $obj_power==NULL ? 0 : json_encode($obj_power);
    }

    /**
     * 更新权限信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function update_power()
    {
        $power_table=config('constants.POWER');

        $arr_update=self::pub_add_update();

        $arr_power_where=[['name',$arr_update['name']],['status',1],['id','<>',$arr_update['power_id']]];
        $arr_power_or_where=[['link',$arr_update['link']],['status',1],['id','<>',$arr_update['power_id']]];
        $obj_power=DB::table($power_table)->select('id')->where($arr_power_where)->orWhere($arr_power_or_where)->first();//查询权限是否存在
        if($obj_power!=NULL)
        {
            $this->arr_return['message']='权限已存在';
            $this->arr_return['error_input']='';
            return json_encode($this->arr_return);
        }

        $arr_update['last_time'] = time();
        $power_id=$arr_update['power_id'];
        unset($arr_update['power_id']);
//var_dump($arr_update);die;
        DB::table($power_table)
            ->where('id', $power_id)
            ->update($arr_update);
        $this->arr_return['status'] = 1;
        $this->arr_return['message'] = '修改成功';
        return json_encode($this->arr_return);
    }

    /**
     * 添加权限
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function add_power()
    {
        $power_table=config('constants.POWER');

        $arr_update=self::pub_add_update();

        $arr_power_where=[['name',$arr_update['name']],['status',1]];
        $arr_power_or_where=[['link',$arr_update['link']],['status',1]];
        $obj_power=DB::table($power_table)->select('id')->where($arr_power_where)->orWhere($arr_power_or_where)->first();//查询权限是否存在
        if($obj_power!=NULL)
        {
            $this->arr_return['message']='权限已存在';
            $this->arr_return['error_input']='';
            return json_encode($this->arr_return);
        }
//        var_dump($arr_power_where);die;

        unset($arr_update['power_id']);
        $arr_update['create_time'] = time();
        $arr_update['last_time'] = time();
        $insert_id=DB::table($power_table)->insertGetId($arr_update);
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
     * 权限信息收集
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
            'name'=>['required',new verfcount(30,'权限名','modal_power_name')],
            'link'=>['required',new verfcount(50,'权限链接','modal_power_link')]
        ];

        $arr_message=[
            'name.*'=>['权限名不能为空','modal_power_name'],
            'link.*'=>['链接名不能为空','modal_power_link'],
        ];

        Validator::make($arr_update,$arr_rule,$arr_message)->validate();

        return $arr_update;
    }
}