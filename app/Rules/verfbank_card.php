<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class verfbank_card implements Rule
{
    private $err_input='';
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($err_input)
    {
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
        $arr_no = str_split($value);
        $last_n = $arr_no[count($arr_no)-1];
        krsort($arr_no);
        $i = 1;
        $total = 0;
        foreach ($arr_no as $n){
            if($i%2==0){
                $ix = $n*2;
                if($ix>=10){
                    $nx = 1 + ($ix % 10);
                    $total += $nx;
                }else{
                    $total += $ix;
                }
            }else{
                $total += $n;
            }
            $i++;
        }
        $total -= $last_n;
        $total *= 9;
        return $last_n == ($total%10);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return [['银行卡号错误',$this->err_input]];
    }
}
