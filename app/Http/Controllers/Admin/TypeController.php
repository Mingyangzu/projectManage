<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\SecondController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use App\Rules\verfcount;
class TypeController extends SecondController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 类型首页
     * @author tuomeikeji
     * @time 2019-04-19
     */
    public function type_index()
    {
        $arr_var=['title'=>'类型管理'];

        $type_table=config('constants.TYPE');

        $arr_post_data=Input::get();
        $arr_var['arr_post_data']=$arr_post_data;
        foreach($arr_post_data as $k=>$v)
        {
            $arr_post_data[$k]=trim($v);
        }

        $arr_type_where=[];//类型where条件

        if(isset($arr_post_data['select_type_name']) && $arr_post_data['select_type_name']!='')
        {
            $arr_type_where[]=[$type_table.'.name','=',$arr_post_data['select_type_name']];
        }
        if(isset($arr_post_data['select_type_status']) && $arr_post_data['select_type_status']!=2)
        {
            $arr_type_where[]=[$type_table.'.status','=',$arr_post_data['select_type_status']];
        }

        $current_page = Input::get("page",1);
        $arr_data = DB::table($type_table)->where($arr_type_where)->orderBy('id', 'desc')->get();
        $items = array_slice(json_decode(json_encode($arr_data),true),($current_page-1)*$this->pub_per_page,$this->pub_per_page);
        $arr_var['type_rows']=count($arr_data);
        $arr_var['arr_type'] = new LengthAwarePaginator($items, $arr_var['type_rows'],$this->pub_per_page);

        $arr_var['type_status']=$this->type_status;
        return view('Admin.type_index',$arr_var);
    }

    /**
     * 获取类型信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function get_type()
    {
        $type_table=config('constants.TYPE');

        $type_id=trim(Input::get('type_id'));
//        var_dump($role_id);die;
        $arr_type_where=[[$type_table.'.id',$type_id]];
        $obj_type=DB::table($type_table)->where($arr_type_where)->first();
//            var_dump(json_encode($obj_project));die;
        return $obj_type==NULL ? 0 : json_encode($obj_type);
    }

    /**
     * 更新类型信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function update_type()
    {
        $type_table=config('constants.TYPE');

        $arr_update=self::pub_add_update();

        $arr_type_where=[['name',$arr_update['name']], ['status',1],['id','<>',$arr_update['type_id']]];
        $obj_type=DB::table($type_table)->select('id')->where($arr_type_where)->first();//查询类型是否存在
        if($obj_type!=NULL)
        {
            $this->arr_return['message']='类型名已存在';
            $this->arr_return['error_input']='';
            return json_encode($this->arr_return);
        }

        $arr_update['last_time'] = time();
        $type_id=$arr_update['type_id'];
        unset($arr_update['type_id']);
//var_dump($arr_update);die;
        DB::table($type_table)
            ->where('id', $type_id)
            ->update($arr_update);
        $this->arr_return['status'] = 1;
        $this->arr_return['message'] = '修改成功';
        return json_encode($this->arr_return);
    }

    /**
     * 添加类型信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function add_type()
    {
        $type_table=config('constants.TYPE');

        $arr_update=self::pub_add_update();

        $arr_type_where=[['name',$arr_update['name']],['status',1]];
        $obj_type=DB::table($type_table)->select('id')->where($arr_type_where)->first();//查询类型是否存在
        if($obj_type!=NULL)
        {
            $this->arr_return['message']='类型名已存在';
            $this->arr_return['error_input']='';
            return json_encode($this->arr_return);
        }

        unset($arr_update['type_id']);
        $arr_update['create_time'] = time();
        $arr_update['last_time'] = time();
        $insert_id=DB::table($type_table)->insertGetId($arr_update);
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
     * 类型信息收集
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
            'name'=>['required',new verfcount(30,'类型名','modal_type_name')],
        ];
        $arr_message=[
            'name.*'=>['类型名不能为空','modal_type_name'],
        ];

        Validator::make($arr_update,$arr_rule,$arr_message)->validate();

        return $arr_update;
    }
}