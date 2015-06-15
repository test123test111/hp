<?php

namespace customer\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use customer\models\LoginForm;
use yii\filters\VerbFilter;
use backend\models\News;
class SiteController extends Controller
{
    public $sidebars=[];
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
			// return $this->goBack();
			return $this->redirect('/material/list');
		}
		return $this->render('login', [
			'model' => $model,
			'news'=> News::find()->orderBy(['id'=>SORT_DESC])->limit(4)->all(),
		]);
	}

	public function actionLogout()
	{
		Yii::$app->user->logout();
		return $this->goHome();
	}
	public function actionError(){
		return $this->render("error");
	}
}
