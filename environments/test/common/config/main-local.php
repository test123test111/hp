<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=aimeizhuyi_test',
            'username' => 'root',
            'password' => 'aimei753951',
            'charset' => 'utf8mb4',
        ],
        'backenddb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=backend',
            'username' => 'root',
            'password' => 'aimei753951',
            'charset' => 'utf8',
        ],
        'redis'=>[
			'hostname'=>'127.0.0.1',
			'port'=>6379,           
            'password'=>'1234567890',   
        ],
    ],
];
