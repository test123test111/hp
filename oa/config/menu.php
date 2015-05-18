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
                [
                    'name' => '权限添加',
                    'route' => 'auth/flushperms',
                ],
            ],
        ],


        'boardroom'=>[
            'name'=>'会议室管理',
            'route'=>'boardroom/list',
            'icon'=>'hdd',
            'subs'=>[
                [
                    'name'=>'会议室列表',
                    'route'=>'boardroom/list',
                ],
                [
                    'name'=>'添加会议室',
                    'route'=>'boardroom/create',
                ],
            ],
        ],
];