<?php
    //创建密码
    function create_password($password='123456',$salt='tmkjkf')
    {
        return sha1(sha1($password).$salt);
    }

    //无限分类
    function getTree(& $all_list, $p_id=0, $deep=0) {
    static $tree = [];
    //遍历所有的数据，判断是否是当前子分类
    foreach($all_list as $row) {
        //判断是否是当前需要查找的子分类
        if ($row->pid == $p_id) {
            //保存到一个结果数组内
            $row->deep = $deep;
            $tree[] = $row;
            //依据当前分类，再递归查找
            getTree($all_list, $row->id, $deep+1);
        }
    }
    return $tree;
}

/**
 * 验证银行卡所属银行
 */
function bank_name($card)
{
    $arr_bank=config('bank_list.bank_list');
    $sub_card=substr($card,0,8);
    if(isset($arr_bank[$sub_card]))
    {
        return $arr_bank[$sub_card];
    }

    $sub_card=substr($card,0,6);
    if(isset($arr_bank[$sub_card]))
    {
        return $arr_bank[$sub_card];
    }

    $sub_card=substr($card,0,5);
    if(isset($arr_bank[$sub_card]))
    {
        return $arr_bank[$sub_card];
    }

    $sub_card=substr($card,0,4);
    if(isset($arr_bank[$sub_card]))
    {
        return $arr_bank[$sub_card];
    }

    return '暂时没有查到银行';
}