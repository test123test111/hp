<?php
return [
	'components' => [
      'db' => [
      	'class' => 'yii\db\Connection',
      	'charset' => 'utf8',
      ],
      'gqueue' => [
            'class' => 'gcommon\components\gqueue\GQueue',
      ],
      'redis' => [
             'class' => 'yii\redis\Connection',
             'port' => '6379',
        ],
      'mail' => [
          'class' => 'yii\swiftmailer\Mailer',
          'transport' => [
              'class' => 'Swift_SmtpTransport',
              'host' => 'localhost',
              'username' => 'username',
              'password' => 'password',
              'port' => '587',
              'encryption' => 'tls',
            ],
      ],
	  ],
];