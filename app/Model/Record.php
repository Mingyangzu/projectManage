<?php

namespace App\Model;

//项目沟通记录
class Record extends Model {

    protected $primaryKey = 'id';
    protected $dateFormat = 'U';


    public function __construct() {
        parent::__construct();
    }

    
    public function getCreatedAtAttribute($value){
        return $value ? date('Y-m-d H:i:s', $value) : '';
    }

    public function getUpdatedAtAttribute($value){
        return $value ? date('Y-m-d H:i:s', $value) : '';
    }
    
    public function getRecordAtAttribute($value){
        return $value ? date('Y-m-d', $value) : '';
    }
    
    
}
