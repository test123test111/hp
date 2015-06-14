<?php
namespace customer\controllers;
use customer\models\Address;
use Yii;

class AddressController extends \yii\web\Controller {
	public $layout = false;

	public function actionList(){
		list($data,$pages,$count) = Address::getAddressByUid(Yii::$app->user->id);
		return $this->render('list',[
			'results'=>$data,
			'pages'=>$pages,
			'count'=>$count,
		]);
	}
}