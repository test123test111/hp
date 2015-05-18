<?php
$rootDir = dirname(dirname(__DIR__));

$params = array_merge(
  require($rootDir . '/common/config/params.php'),
  require($rootDir . '/common/config/params-local.php'),
  require(__DIR__ . '/params.php'),
  require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'oa',
    'basePath' => dirname(__DIR__),
    'vendorPath' => $rootDir . '/vendor',
    'controllerNamespace' => 'oa\controllers',
    'language'=>'zh-CN',
    'aliases' => [
        '@view' => '@app/views'
    ],
    'modules'=>[
        'gridview'=>[
            'class'=>'\kartik\grid\Module',
        ],
        'datecontrol'=>[
            'class' => 'kartik\datecontrol\Module',
        ],
    ],
    'extensions' => require($rootDir . '/vendor/yiisoft/extensions.php'),
    'components' => [
        'session' => [
            'class' => 'yii\web\DbSession',
            'db'=>'backenddb',
            'timeout'=> 3600*24,
        ],
        'authManager' =>[
            'class'=>'yii\rbac\DbManager',
            'db'=>'backenddb',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],
            ],
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
        'user' => [
            'class' => backend\components\ManagerUser::className(),
            'identityClass' => 'backend\models\Manager',//common\models\User
            'enableAutoLogin' => true,
        ],
        // 'RoleMenu' =>[
        //     'class'=>'backend\components\RoleMenu',
        // ],
        'notify' =>[
            'class'=>'common\components\Notification',
        ],
        'log' => [
              'targets' => [
                  'file' => [
                      'class' => 'yii\log\FileTarget',
                      'levels' => ['trace', 'info'],
                      'categories' => ['yii\*'],
                  ],
                  'email' => [
                      'class' => 'yii\log\EmailTarget',
                      'levels' => ['error', 'warning'],
                      'message' => [
                          'to' => 'admin@example.com',
                      ],
                  ],
              ],
          ],
          
       'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
