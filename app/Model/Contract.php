<?php

namespace App\Model;

class Contract extends Model {

    protected $table = 'contract';
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

}
