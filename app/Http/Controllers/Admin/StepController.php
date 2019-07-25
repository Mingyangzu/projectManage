<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\SecondController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use App\Rules\verfcount;
class StepController extends SecondController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 进度首页
     * @author tuomeikeji
     * @time 2019-04-19
     */
    public function step_index()
    {
        $arr_var=['title'=>'进度管理'];

        $step_table=config('constants.STEP');

        $arr_post_data=Input::get();
        $arr_var['arr_post_data']=$arr_post_data;
        foreach($arr_post_data as $k=>$v)
        {
            $arr_post_data[$k]=trim($v);
        }

        $arr_step_where=[];//进度where条件

        if(isset($arr_post_data['select_step_name']) && $arr_post_data['select_step_name']!='')
        {
            $arr_step_where[]=[$step_table.'.name','=',$arr_post_data['select_step_name']];
        }
        if(isset($arr_post_data['select_step_status']) && $arr_post_data['select_step_status']!=2)
        {
            $arr_step_where[]=[$step_table.'.status','=',$arr_post_data['select_step_status']];
        }

        $current_page = Input::get("page",1);
        $arr_data = DB::table($step_table)->where($arr_step_where)->orderBy('id', 'desc')->get();
        $items = array_slice(json_decode(json_encode($arr_data),true),($current_page-1)*$this->pub_per_page,$this->pub_per_page);
        $arr_var['step_rows']=count($arr_data);
        $arr_var['arr_step'] = new LengthAwarePaginator($items, $arr_var['step_rows'],$this->pub_per_page);

        $arr_var['step_status']=$this->step_status;
        return view('Admin.step_index',$arr_var);
    }

    /**
     * 获取进度信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function get_step()
    {
        $step_table=config('constants.STEP');

        $step_id=trim(Input::get('step_id'));
//        var_dump($role_id);die;
        $arr_step_where=[[$step_table.'.id',$step_id]];
        $obj_step=DB::table($step_table)->where($arr_step_where)->first();
//            var_dump(json_encode($obj_project));die;
        return $obj_step==NULL ? 0 : json_encode($obj_step);
    }

    /**
     * 更新进度信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function update_step()
    {
        $step_table=config('constants.STEP');

        $arr_update=self::pub_add_update();

        $arr_step_where=[['name',$arr_update['name']], ['status',1],['id','<>',$arr_update['step_id']]];
        $obj_step=DB::table($step_table)->select('id')->where($arr_step_where)->first();//查询进度是否存在
        if($obj_step!=NULL)
        {
            $this->arr_return['message']='进度名已存在';
            $this->arr_return['error_input']='';
            return json_encode($this->arr_return);
        }

        $arr_update['last_time'] = time();
        $step_id=$arr_update['step_id'];
        unset($arr_update['step_id']);
//var_dump($arr_update);die;
        DB::table($step_table)
            ->where('id', $step_id)
            ->update($arr_update);
        $this->arr_return['status'] = 1;
        $this->arr_return['message'] = '修改成功';
        return json_encode($this->arr_return);
    }

    /**
     * 添加进度信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function add_step()
    {
        $step_table=config('constants.STEP');

        $arr_update=self::pub_add_update();

        $arr_step_where=[['name',$arr_update['name']],['status',1]];
        $obj_step=DB::table($step_table)->select('id')->where($arr_step_where)->first();//查询进度是否存在
        if($obj_step!=NULL)
        {
            $this->arr_return['message']='进度名已存在';
            $this->arr_return['error_input']='';
            return json_encode($this->arr_return);
        }

        unset($arr_update['step_id']);
        $arr_update['create_time'] = time();
        $arr_update['last_time'] = time();
        $insert_id=DB::table($step_table)->insertGetId($arr_update);
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
     * 进度信息收集
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
            'name'=>['required',new verfcount(30,'进度名','modal_step_name')],
        ];
        $arr_message=[
            'name.*'=>['进度名不能为空','modal_step_name'],
        ];

        Validator::make($arr_update,$arr_rule,$arr_message)->validate();

        return $arr_update;
    }
}