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
          'htmlLayout'=>false,
          'transport' => [
              'class' => 'Swift_SmtpTransport',
              'host' => 'smtp.exmail.qq.com',
              'username' => 'service@yt-logistics.cn',
              'password' => 'yltd54321',
              'port' => '587',
              'encryption' => 'tls',
            ],
      ],
	  ],
];