<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class verfint implements Rule
{
    private $num=30;
    private $message='';
    private $err_input='';
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($num,$message,$err_input)
    {
        $this->num=$num;
        $this->message=$message;
        $this->err_input=$err_input;
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
        if(floor($value)!=$value || $value>$this->num){
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
        return [[$this->message.'必须为正整数并且不能大于'.$this->num,$this->err_input]];
    }
}
