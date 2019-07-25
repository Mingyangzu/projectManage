<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\SecondController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use App\Rules\verfcount;
use App\Rules\verfint;
class MenuController extends SecondController
{
    public function __construct()
    {
//        var_dump($this->arr_return);
        parent::__construct();
    }

    /**
     * 导航首页
     * @author tuomeikeji
     * @time 2019-04-19
     */
    public function menu_list()
    {
        $arr_var=['title'=>'导航菜单管理'];
        $menu_table=config('constants.MENU');
        $menu_list_table=config('constants.MENU_LIST');

        $arr_post_data=Input::get();

        $arr_var['arr_post_data']=$arr_post_data;
        foreach($arr_post_data as $k=>$v)
        {
            $arr_post_data[$k]=trim($v);
        }

        $arr_menu_where=[[$menu_table.'.link','<>','menu_list'],[$menu_list_table.'.status',1]];//where条件

        if(isset($arr_post_data['select_menu_name']) && $arr_post_data['select_menu_name']!='')
        {
            $arr_menu_where[]=[$menu_table.'.name','=',$arr_post_data['select_menu_name']];
        }
        if(isset($arr_post_data['select_menu_status']) && $arr_post_data['select_menu_status']!=2)
        {
            $arr_menu_where[]=[$menu_table.'.status','=',$arr_post_data['select_menu_status']];
        }
        if(isset($arr_post_data['select_is_one_menu']) && $arr_post_data['select_is_one_menu']!=2)
        {
            $arr_menu_where[]=$arr_post_data['select_is_one_menu']==0 ? [$menu_table.'.pid','=',0] : [$menu_table.'.pid','<>',0];
        }
        if(isset($arr_post_data['select_menu_type']) && $arr_post_data['select_menu_type']!=0)
        {
            $arr_menu_where[]=[$menu_table.'.type','=',$arr_post_data['select_menu_type']];
        }
//var_dump($arr_menu_where);
        $current_page = Input::get("page",1);
        $arr_data = DB::table($menu_table)->join($menu_list_table,$menu_list_table.'.id','=',$menu_table.'.type')->select($menu_table.'.*',$menu_list_table.'.name as menu_list_name')->where($arr_menu_where)->orderBy('order','asc')->orderBy('id', 'asc')->get();
        $items = array_slice(json_decode(json_encode($arr_data),true),($current_page-1)*$this->pub_per_page,$this->pub_per_page);
//var_dump($items);
        $obj_one_menu= DB::table($menu_table)->where([['status',1],['pid',0]])->get();//一级导航
        $obj_menu_category= DB::table($menu_list_table)->where([['status',1]])->get();//导航分类
        $arr_var['menu_rows']=count($arr_data);
        $arr_var['arr_menu'] = new LengthAwarePaginator($items, $arr_var['menu_rows'],$this->pub_per_page);
        $arr_var['menu_status']=$this->menu_status;
        $arr_var['obj_one_menu']=$obj_one_menu;
        $arr_var['obj_menu_category']=$obj_menu_category;
        return view('Admin.menu_list',$arr_var);
    }

    /**
     * 获取导航菜单信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function get_menu()
    {
        $menu_table=config('constants.MENU');

        $menu_id=trim(Input::get('menu_id'));
        $arr_menu_where=[[$menu_table.'.id',$menu_id]];
        $obj_menu=DB::table($menu_table)->where($arr_menu_where)->first();
//            var_dump(json_encode($obj_project));die;
        return $obj_menu==NULL ? 0 : json_encode($obj_menu);
    }

    /**
     * 更新导航菜单信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function update_menu()
    {
        $menu_table=config('constants.MENU');

        $arr_update=self::pub_add_update();

        $arr_menu_name_where=[['name',$arr_update['name']],['status',1],['id','<>',$arr_update['menu_id']]];
        $obj_menu_name=DB::table($menu_table)->select('id')->where($arr_menu_name_where)->first();//查询导航名字是否存在
        if($obj_menu_name!=NULL)
        {
            $this->arr_return['message']='导航名字已存在';
            $this->arr_return['error_input']='modal_menu_name';
            return json_encode($this->arr_return);
        }

        $arr_menu_link_where=[['status',1],['id','<>',$arr_update['menu_id']],['link',$arr_update['link']]];
        $obj_menu_name=DB::table($menu_table)->select('id')->where($arr_menu_link_where)->first();//查询导航链接是否存在
        if($obj_menu_name!=NULL)
        {
            $this->arr_return['message']='导航链接已存在';
            $this->arr_return['error_input']='modal_menu_link';
            return json_encode($this->arr_return);
        }

        $arr_update['last_time'] = time();
        $menu_id=$arr_update['menu_id'];
        unset($arr_update['menu_id']);
//var_dump($arr_update);die;
        DB::table($menu_table)
            ->where('id', $menu_id)
            ->update($arr_update);
        $this->arr_return['status'] = 1;
        $this->arr_return['message'] = '修改成功';
        return json_encode($this->arr_return);
    }

    /**
     * 添加导航菜单信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function add_menu()
    {
        $menu_table=config('constants.MENU');

        $arr_update=self::pub_add_update();

        $arr_menu_name_where=[['name',$arr_update['name']],['status',1]];
        $obj_menu_name=DB::table($menu_table)->select('id')->where($arr_menu_name_where)->first();//查询导航名字是否存在
        if($obj_menu_name!=NULL)
        {
            $this->arr_return['message']='导航名字已存在';
            $this->arr_return['error_input']='modal_menu_name';
            return json_encode($this->arr_return);
        }

        $arr_menu_link_where=[['status',1],['link',$arr_update['link']]];
        $obj_menu_name=DB::table($menu_table)->select('id')->where($arr_menu_link_where)->first();//查询导航链接是否存在
        if($obj_menu_name!=NULL)
        {
            $this->arr_return['message']='导航链接已存在';
            $this->arr_return['error_input']='modal_menu_link';
            return json_encode($this->arr_return);
        }

        unset($arr_update['menu_id']);
        $arr_update['create_time'] = time();
        $arr_update['last_time'] = time();
        $insert_id=DB::table($menu_table)->insertGetId($arr_update);
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
     * 导航菜单信息收集
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
            'name'=>['required',new verfcount(10,'导航名字','modal_menu_name')],
            'order'=>['required',new verfint(4294967295,'排序','modal_menu_order')],
            'link'=>['required',new verfcount(50,'链接名字','modal_menu_link')]
        ];
        $arr_message=[
            'name.*'=>['导航名不能为空','modal_menu_name'],
            'link.*'=>['链接名字不能为空','modal_menu_link'],
            'order.*'=>['排序不能为空','modal_menu_order'],
        ];

        Validator::make($arr_update,$arr_rule,$arr_message)->validate();

        return $arr_update;
    }

    /**
     * 导航分类首页
     * @author tuomeikeji
     * @time 2019-05-10
     */
    public function menu_cate_list()
    {
        $arr_var=['title'=>'导航分类'];
        $menu_list_table=config('constants.MENU_LIST');

        $arr_post_data=Input::get();

        $arr_var['arr_post_data']=$arr_post_data;
        foreach($arr_post_data as $k=>$v)
        {
            $arr_post_data[$k]=trim($v);
        }

        $arr_menu_cate_where=[];//where条件

        if(isset($arr_post_data['select_menu_cate_name']) && $arr_post_data['select_menu_cate_name']!='')
        {
            $arr_menu_cate_where[]=[$menu_list_table.'.name','=',$arr_post_data['select_menu_cate_name']];
        }
        if(isset($arr_post_data['select_menu_cate_status']) && $arr_post_data['select_menu_cate_status']!=2)
        {
            $arr_menu_cate_where[]=[$menu_list_table.'.status','=',$arr_post_data['select_menu_cate_status']];
        }

        $current_page = Input::get("page",1);
        $arr_data = DB::table($menu_list_table)->where($arr_menu_cate_where)->orderBy('id', 'desc')->get();
        $items = array_slice(json_decode(json_encode($arr_data),true),($current_page-1)*$this->pub_per_page,$this->pub_per_page);
//var_dump($items);
        $arr_var['menu_cate_rows']=count($arr_data);
        $arr_var['arr_cate_menu'] = new LengthAwarePaginator($items, $arr_var['menu_cate_rows'],$this->pub_per_page);
        $arr_var['menu_status']=$this->menu_status;
        return view('Admin.menu_cate_list',$arr_var);
    }

    /**
     * 获取导航菜单信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function get_menu_cate()
    {
        $menu_list_table=config('constants.MENU_LIST');

        $menu_cate_id=trim(Input::get('menu_cate_id'));
        $arr_menu_cate_where=[[$menu_list_table.'.id',$menu_cate_id]];
        $obj_menu_cate=DB::table($menu_list_table)->where($arr_menu_cate_where)->first();
//            var_dump(json_encode($obj_project));die;
        return $obj_menu_cate==NULL ? 0 : json_encode($obj_menu_cate);
    }

    /**
     * 更新导航菜单信息
     * @author tuomeikeji
     * @time 2019-04-18
     */
    public function update_menu_cate()
    {
        $menu_list_table=config('constants.MENU_LIST');

        $arr_update=self::pub_add_update_cate();

        $arr_menu_name_where=[['name',$arr_update['name']],['status',1],['id','<>',$arr_update['menu_cate_id']]];
        $obj_menu_name=DB::table($menu_list_table)->select('id')->where($arr_menu_name_where)->first();//查询导航名字是否存在
        if($obj_menu_name!=NULL)
        {
            $this->arr_return['message']='导航名字已存在';
            $this->arr_return['error_input']='modal_menu_cate_name';
            return json_encode($this->arr_return);
        }

        $arr_menu_describe_where=[['status',1],['describe',$arr_update['describe']],['id','<>',$arr_update['menu_cate_id']]];
        $obj_menu_name=DB::table($menu_list_table)->select('id')->where($arr_menu_describe_where)->first();//查询导航描述是否存在
        if($obj_menu_name!=NULL)
        {
            $this->arr_return['message']='导航描述已存在';
            $this->arr_return['error_input']='modal_menu_cate_describe';
            return json_encode($this->arr_return);
        }

        $arr_update['last_time'] = time();
        $menu_cate_id=$arr_update['menu_cate_id'];
        unset($arr_update['menu_cate_id']);
//var_dump($arr_update);die;
        DB::table($menu_list_table)
            ->where('id', $menu_cate_id)
            ->update($arr_update);
        $this->arr_return['status'] = 1;
        $this->arr_return['message'] = '修改成功';
        return json_encode($this->arr_return);
    }

    /**
     * 添加导航菜单信息
     * @author tuomeikeji
     * @time 2019-05-10
     */
    public function add_menu_cate()
    {
        $menu_list_table=config('constants.MENU_LIST');

        $arr_update=self::pub_add_update_cate();

        $arr_menu_name_where=[['status',1],['name',$arr_update['name']]];
        $obj_menu_name=DB::table($menu_list_table)->select('id')->where($arr_menu_name_where)->first();//查询导航名字是否存在
        if($obj_menu_name!=NULL)
        {
            $this->arr_return['message']='导航分类名已存在';
            $this->arr_return['error_input']='modal_menu_cate_name';
            return json_encode($this->arr_return);
        }

        $arr_menu_describe_where=[['status',1],['describe',$arr_update['describe']]];
        $obj_menu_name=DB::table($menu_list_table)->select('id')->where($arr_menu_describe_where)->first();//查询导航描述是否存在
        if($obj_menu_name!=NULL)
        {
            $this->arr_return['message']='导航描述已存在';
            $this->arr_return['error_input']='modal_menu_cate_describe';
            return json_encode($this->arr_return);
        }
        unset($arr_update['menu_cate_id']);
        $arr_update['create_time'] = time();
        $arr_update['last_time'] = time();
        $insert_id=DB::table($menu_list_table)->insertGetId($arr_update);
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
     * 导航菜单信息收集
     * @author tuomeikeji
     * @time 2019-04-18
     */
    private function pub_add_update_cate()
    {
        $arr_update=Input::get();
//        var_dump($arr_update);die;
        unset($arr_update['_token']);
        foreach($arr_update as $k=>$v)
        {
            $arr_update[$k]=trim(htmlspecialchars($v));
        }
        $arr_rule=[
            'name'=>['required',new verfcount(30,'导航名字','modal_menu_cate_name')],
            'describe'=>['required',new verfcount(30,'导航描述','modal_menu_cate_describe')]
        ];
        $arr_message=[
            'name.*'=>['导航分类名不能为空','modal_menu_cate_name'],
            'describe.*'=>['导航描述不能为空','modal_menu_cate_describe'],
        ];

        Validator::make($arr_update,$arr_rule,$arr_message)->validate();

        return $arr_update;
    }
}