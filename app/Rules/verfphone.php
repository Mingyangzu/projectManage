<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class verfphone implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $err_input='';
    public function __construct($input_error='modal_customer_tel')
    {
        //
        $this->err_input=$input_error;
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
        if(!preg_match("/^1[34578]\d{9}$/", $value)){
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
        return [['手机号格式不正确',$this->err_input]];
    }
}
