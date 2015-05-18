<?php

namespace oa\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\models\LoginForm;
use yii\filters\VerbFilter;

class SiteController extends Controller
{
    public $layout = false;
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                        'roles' =>['?'],
                    ],
                    [
                        'actions' => ['logout', 'index','error'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actions(){
        return [
            'error'=>[
                'class'=>'yii\web\ErrorAction',
            ],
        ];
    }


    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load($_POST) && $model->login()) {
            return $this->goBack();
        }
        
        return $this->render('login', [
                'model' => $model,
            ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
    public function actionError(){
        ini_set('display_errors', 'On');
        ini_set('display_startup_errors', 'On');
        error_reporting(E_ALL);
        set_error_handler(function ($errorNumber, $message, $errfile, $errline) {
            switch ($errorNumber) {
                case E_ERROR :
                    $errorLevel = 'Error';
                    break;

                case E_WARNING :
                    $errorLevel = 'Warning';
                    break;

                case E_NOTICE :
                    $errorLevel = 'Notice';
                    break;

                default :
                    $errorLevel = 'Undefined';
            }

            echo '<br/><b>' . $errorLevel . '</b>: ' . $message . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b><br/>';
        });

        set_exception_handler(function ($e) {
            print '<pre>' . get_class($e) . " thrown within the exception handler. Message: " . $e->getMessage() . " on line " . $e->getLine() . ' <br />';
            echo $e->getTraceAsString() . '</pre>';
        });

    }
}
