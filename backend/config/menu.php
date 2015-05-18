<?php
return [
        'managers' => [
            'name' => '管理员',
            'route' => 'manager/admin',
            'icon'=>'user',
            'style'=>'',
            'subs' => [
                [
                    'name' => '管理员列表',
                    'route' => 'manager/admin',
                ],
                [
                    'name' => '添加管理员',
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
        'printer' => [
            'name' => '打印机',
            'route' => 'printer/list',
            'icon'=>'print',
            'style'=>'',
            'subs' => [
                [
                    'name' => '打印机管理',
                    'route' => 'printer/list',
                ],
                [
                    'name' => '添加打印机',
                    'route' => 'printer/create',
                ],
            ],
        ],
        'check' => [
            'name' => '验货',
            'route' => 'check/index',
            'icon'=>'check',
            'style'=>'',
        ],
        'input' => [
            'name' => '入库',
            'route' => 'input/index',
            'icon'=>'inbox',
            'style'=>'',
        ],
        'stock' => [
            'name' => '在库',
            'route' => 'stock/index',
            'icon'=>'tasks',
            'style'=>'',
        ],
        'output' => [
            'name' => '出库',
            'route' => 'output/index',
            'icon'=>'share',
            'style'=>'',
        ],

];