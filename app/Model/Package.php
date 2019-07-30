<?php

namespace App\Model;

class Package extends Model {

    protected $table = 'packages';
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
    

}
