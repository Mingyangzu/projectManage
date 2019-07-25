<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class verfmoney implements Rule
{
    private $error_input='';
    private $max='';
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($max='999999999999999',$error_input='modal_project_money')
    {
        $this->error_input=$error_input;
        $this->max=$max;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $count=self::_getFloatLength($value);
        if($count>2 || $value>$this->max)
        {
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return [['金额小数不能超过两位并且金额不能大于'.$this->max.'元',$this->error_input]];;
    }


    private function _getFloatLength($num) {
        $count = 0;

        $temp = explode ( '.', $num );

        if (sizeof ( $temp ) > 1) {
            $decimal = end ( $temp );
            $count = strlen ( $decimal );
        }

        return $count;
    }
}
