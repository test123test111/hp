<?php
return [
    'components' => [
        'backenddb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=backend',
            'username' => 'root',
            'password' => 'aimei753951',
            'charset' => 'utf8',
        ],
        'request'=>[
            'cookieValidationKey'=>"cnOOB2bIo8tIod1K3th3BETtjP112233",
        ],
    ],
];
