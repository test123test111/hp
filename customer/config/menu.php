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
        'logistic' => [
            'name' => '物流管理',
            'route' => 'logistic/domestic',
            'icon'=>'truck',
            'style'=>'',
            'subs' => [
                [
                    'name' => '国内物流',
                    'route' => 'logistic/domestic',
                ],
            ],
        ],
        'uservip' => [
            'name' => 'VIP用户管理',
            'route' => 'uservip/list',
            'icon' => 'credit-card',
            'style' => '',
            'subs' => [
                [
                    'name' => 'VIP用户信息',
                    'route' => 'uservip/list',
                ],
                [
                    'name' => 'VIP用户购物车',
                    'route' => 'uservip/cart',
                ]


            ],
        ],
        'position'=>[
            'name'=>'运营位管理',
            'route'=>'position/list',
            'icon'=>'hdd',
            'subs'=>[
                [
                    'name'=>'运营位列表',
                    'route'=>'position/list',
                ],
                // [
                //     'name'=>'创建运营位',
                //     'route'=>'position/create',
                // ],
            ],
        ],
        'commodity' => [
            'name'  => '商品管理',
            'route' => 'brand/list',
            'icon'  => 'list',
            'style' => '',
            'subs'  => [
                [
                    'name' => '品牌管理',
                    'route' => 'brand/list',
                ],
                [
                    'name' => '类目管理',
                    'route' => 'category/list',
                ],
            ],
        ],
];