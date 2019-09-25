<?php

return [
    'project_type_id' => [
        1 => 'App',
        2 => 'Web',
        3 => '小程序',
        4 => '公众号',
        5 => '网站',
        6 => '商城',
        7 => 'OA系统',
        8 => '其他',
    ],
    'customer_source' => [//客户来源 注意 可以改名字 不要改id 
        1 => '百度关键词搜索',
        2 => '360关键词搜索',
        3 => '百度推广',
        4 => '360推广',
        5 => '招投标信息',
        6 => '朋友介绍',
        7 => '老客户推荐',
        8 => '软文链接',
        9 => '淘宝',
        0 => '其他',
    ],
    'customer_type' => [//客户类型 注意 可以改名字 不要改id
        0 => '个人',
        1 => '企业',
    ],
    'project_pay_status' => [//项目收款状态 注意 可以改名字 不要改id  、、、
        0 => '未付款',
        1 => '预付款已收',
        2 => '中款1已收',
        3 => '中款2已收',
        4 => '中款3已收',
        5 => '中款4已收',
        9 => '尾款已收',
    ],
    'contract_status' => [//合同状态 注意 可以改名字 不要改id 
        0 => '已作废',
        1 => '跟踪中',
        2 => '已签约',
        3 => '派单中',
        4 => '开发中',
        5 => '收款期',
        9 => '已完结',
        10 => '转售后',
        11 => '暂搁置',
    ],
    'gender' => [ // 客户 性别
        0 => '女',
        1 => '男',
    ],
    'process_status' => [
        0 => '第一阶段开发',
        1 => '第二阶段开发',
        2 => '第三阶段开发',
        3 => '第四阶段开发',
        4 => '第五阶段开发',
        10 => '技术负责人总结',
        11 => '监督负责人总结',
        12 => '绩效考核',
        99 => '待完结',
        100 => '已完结',
    ],
    'develop_status' => [
        0 => '第一阶段开发',
        1 => '第二阶段开发',
        2 => '第三阶段开发',
        3 => '第四阶段开发',
        4 => '第五阶段开发',
        10 => '技术负责人总结',
        11 => '监督负责人总结',
        12 => '绩效考核',
        99 => '待完结',
    ],
];

