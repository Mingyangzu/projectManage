<?php

namespace App\Model;

class Process extends Model {

    protected $table = 'process';
    protected $primaryKey = 'id';
    
    public function __construct() {
        parent::__construct();
    }
      

}
