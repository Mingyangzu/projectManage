<?php

namespace App\Model;

//客户
class Projects extends Model {

    protected $table = 'project';
    protected $primaryKey = 'id';
    protected $dateFormat = 'U';


    protected $appends = ['surplus'];

    public function __construct() {
        parent::__construct();
    }


    public function contractMany() {
        return $this->belongsTo('App\Model\Contract', 'project_id', 'id');
    }
    
    public function getSurplusAttribute(){
        return $this->deliver_date ? round((strtotime($this->deliver_date) - time()) / 86400) : 0;
    }
    
    
    public function getCreatedAtAttribute($value){
        return $value ? date('Y-m-d H:i:s', $value) : '';
    }

    public function getUpdatedAtAttribute($value){
        return $value ? date('Y-m-d H:i:s', $value) : '';
    }

}
