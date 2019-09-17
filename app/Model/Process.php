<?php

namespace App\Model;

class Process extends Model {

    protected $table = 'process';
    protected $primaryKey = 'id';
    protected $dateFormat = 'U';
    
    public function __construct() {
        parent::__construct();
    }
      

//    public function getTakeEffectTimeAttribute($value){
//        return  $value ? date('Y-m-d', $value) : '';
//    }
//    
//    
//    public function getContractTimeAttribute($value){
//        return $value ? date('Y-m-d', $value) : '';
//    } 
//    
//    public function getEndTimeAttribute($value){
//        return $value ? date('Y-m-d', $value) : '';
//    }    
    

}
