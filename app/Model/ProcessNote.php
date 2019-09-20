<?php

namespace App\Model;

class ProcessNote extends Model {

    protected $table = 'process_note';
    protected $primaryKey = 'id';
    
    public function __construct() {
        parent::__construct();
    }
      

}
