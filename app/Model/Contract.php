<?php

namespace App\Model;

class Contract extends Model {

    protected $table = 'contract';
    protected $primaryKey = 'id';
    protected $dateFormat = 'U';

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'last_time';
    
    
    public function __construct() {
        parent::__construct();
    }
      

    public function getTakeEffectTimeAttribute($value){
        return  $value ? date('Y-m-d', $value) : '';
    }
    
    
    public function getContractTimeAttribute($value){
        return $value ? date('Y-m-d', $value) : '';
    } 
    
    public function getEndTimeAttribute($value){
        return $value ? date('Y-m-d', $value) : '';
    }    
    

}
