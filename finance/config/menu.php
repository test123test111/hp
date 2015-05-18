<?php
return [
        'managers' => [
            'name' => '用户',
            'route' => 'manager/admin',
            'icon'=>'user',
            'style'=>'',
            'subs' => [
                [
                    'name' => '用户列表',
                    'route' => 'manager/admin',
                ],
                [
                    'name' => '添加用户',
                    'route' => 'manager/create',
                ],
                [
                    'name' => '角色列表',
                    'route' => 'auth/rolelist',
                ],
                // [
                //     'name' => '分配权限',
                //     'route' => 'auth/assign',
                // ],
                [
                    'name' => '权限添加',
                    'route' => 'auth/flushperms',
                ],
            ],
        ],
        'refund' => [
            'name' => '退款管理',
            'route' => 'refund/list',
            'icon'=>'reply',
            'style'=>'',
            'subs' => [
                [
                    'name' => '退款列表',
                    'route' => 'refund/list',
                ],
            ],
        ],
        'balance' => [
            'name' => '结算管理',
            'route' => 'settlement/list',
            'icon'=>'yen',
            'style'=>'',
            'subs' => [
                [
                    'name' => '买手结算',
                    'route' => 'settlement/list',
                ],
                [
                    'name' => '历史结算',
                    'route' => 'settlement/history',
                ],
            ],
        ],
];