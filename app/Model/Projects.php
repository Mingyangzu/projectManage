<?php

namespace App\Model;

//客户
class Projects extends Model {

    protected $table = 'project';
    protected $primaryKey = 'id';

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'last_time';

    public function __construct() {
        parent::__construct();
    }

    // 统计用户数 、本月新增数
    public static function census() {

        $datestr = strtotime(date('Y-m-01', time()));
        return self::where('create_time', '>=', $datestr)->count();
    }
    
    
    public function contractMany(){
        return $this->belongsTo('App\Model\Contract', 'project_id', 'id');
    }
    

}
