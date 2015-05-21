<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=yltd',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'redis'=>[
			'hostname'=>'127.0.0.1',
			'port'=>6379,           
        ],
    ],
];
