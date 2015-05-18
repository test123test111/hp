<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=101.66.253.168;dbname=aimeizhuyi_test',
            'username' => 'root',
            'password' => 'aimei753951',
            'charset' => 'utf8',
        ],
        'backenddb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=101.66.253.168;dbname=backend',
            'username' => 'root',
            'password' => 'aimei753951',
            'charset' => 'utf8',
        ],
        'redis'=>[
			'hostname'=>'101.66.253.168',
			'port'=>6379,           
            'password'=>'1234567890',   
        ],
    ],
];
