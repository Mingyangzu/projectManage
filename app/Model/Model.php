<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model as SysModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends SysModel {

    use SoftDeletes;
    public function __construct() {
        parent::__construct();
    }

}
