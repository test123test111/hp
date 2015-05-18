<?php

return [
    'components' => [
        'request' => [
            'cookieValidationKey' => "cnOOB2bIo8tIod1K3th3BETtjP1s89233",
        ],
        'backenddb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=backenddb',
            'username' => 'root',
            'password' => 'aimei753951',
            'charset' => 'utf8',
        ],
        'oa' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=oa',
            'username' => 'root',
            'password' => 'aimei753951',
            'charset' => 'utf8',
        ],
    ],
];
