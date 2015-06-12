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
          'viewPath' => '@customer/mail',
          'transport' => [
              'class' => 'Swift_SmtpTransport',
              'host' => 'smtp.exmail.qq.com',
              'username' => 'service@yt-logistics.cn',
              'password' => 'yltd12345',
              // 'port' => '587',
              // 'encryption' => 'tls',
            ],
      ],
	  ],
];