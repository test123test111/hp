<?php
namespace customer\controllers;
use customer\models\Address;
use Yii;

class AddressController extends \yii\web\Controller {
	public $layout = false;

	public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['list', 'import'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
	public function actionList(){
		list($data,$pages,$count) = Address::getAddressByUid(Yii::$app->user->id);
		return $this->render('list',[
			'results'=>$data,
			'pages'=>$pages,
			'count'=>$count,
		]);
	}
	/**
	 * import customer address excel 
	 * @return [type] [description]
	 */
	public function actionImport(){
		$result = Address::getDatas(Yii::$app->user->id);
		$filename = '收货人信息.csv';
		header("Content-type:text/csv;charset=utf-8");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        print(chr(0xEF).chr(0xBB).chr(0xBF));
        echo $result;
	}
}