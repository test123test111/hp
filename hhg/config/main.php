<?php
$rootDir = dirname(dirname(__DIR__));

$params = array_merge(
  require($rootDir . '/common/config/params.php'),
  require($rootDir . '/common/config/params-local.php'),
  require(__DIR__ . '/params.php'),
  require(__DIR__ . '/params-local.php')
);

return [
	'id' => 'yltd-backend',
	'basePath' => dirname(__DIR__),
	'vendorPath' => $rootDir . '/vendor',
	'controllerNamespace' => 'hhg\controllers',
  'language'=>'zh-CN',
  'bootstrap' => ['log'],
	'modules'=>[
		'gii' => 'yii\gii\Module',
    'gridview'=>[
        'class'=>'\kartik\grid\Module',
    ],
	],
	'extensions' => require($rootDir . '/vendor/yiisoft/extensions.php'),
	'components' => [
    'authManager' =>[
        'class'=>'yii\rbac\DbManager',
    ],
		'view' => [
            'renderers' => [
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'options' => [
                        'cache' => false,
                        'debug'=>true,
                        'strict_variables'=>true,
                    ],
                    'extensions'=>['common\extensions\Twig\GTwigExtension'],
                    'globals' => [
                        'html' => '\yii\helpers\Html',
                        'pos_begin' => \yii\web\View::POS_BEGIN,
                        'activeform' =>'\yii\bootstrap\ActiveForm',
                        'kactiveform'=>'\kartik\widgets\ActiveForm',
                        'dialog' =>'\yii\jui\Dialog',
                        'appasset' =>'backend\assets\AppAsset',
                    ],
                    'functions' => [
                        't' => '\Yii::t',
                        'json_encode' => '\yii\helpers\Json::encode',
                        // 'str_repeat'=>'str_tree',
                        // 'grid_widget' =>'\yii\grid\GridView::widget',
                        // 'form_begin' =>'\yii\widgets\ActiveForm::begin',
                        // 'form_end' =>'\yii\widgets\ActiveForm::end',
                    ]
                ],

                // 'viewPath'=>'@backend/views',
            ],
            'defaultExtension'=>'twig',
        ],
        'urlManager'=>[
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules'=>[
            	'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
            	'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            	'<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
            ]
        ],
        'budget'=>[
            'class'=>'common\components\Budget',
        ],
        'shoppingCart' => [
            'class' => 'hhg\components\ShoppingCart',
        ],
        'user' => [
            'class' => hhg\components\ManagerUser::className(),
            'identityClass' => 'hhg\models\Hhg',//common\models\User
            'enableAutoLogin' => true,
        ],
        'RoleMenu' =>[
            'class'=>'backend\components\RoleMenu',
        ],
        'log' => [
            'targets' => [
                [
                  'class' => 'yii\log\FileTarget',
                  'levels' => ['error', 'warning'],
                ],
            ],
        ],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
	],
	'params' => $params,
];
