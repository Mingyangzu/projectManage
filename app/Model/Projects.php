<?php

namespace App\Model;

//客户
class Projects extends Model {

    protected $table = 'project';
    protected $primaryKey = 'id';
    protected $dateFormat = 'U';

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'last_time';

    protected $appends = ['surplus', 'created_date'];

    public function __construct() {
        parent::__construct();
    }


    public function contractMany() {
        return $this->belongsTo('App\Model\Contract', 'project_id', 'id');
    }
    
    public function getSurplusAttribute(){
        return $this->deliver_date ? round((strtotime($this->deliver_date) - time()) / 86400) : 0;
    }
    
    public function getCreatedDateAttribute(){
        return $this->create_time ? date('Y-m-d', strtotime($this->create_time)) : '';
    }
    
    
    public function getCreateTimeAttribute($value){
        return $value ? date('Y-m-d', $value) : '';
    }

    public function getLastTimeAttribute($value){
        return $value ? date('Y-m-d', $value) : '';
    }

    public function getTypeIdAttribute($value){
        return $value ? explode(',', $value) : '';
    }
}
