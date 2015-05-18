<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=aimeizhuyi',
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
            'hostname'=>'10.175.205.181',
            'port'=>9736,           
            'password'=>'1234567890',   
        ],
    ],
];
