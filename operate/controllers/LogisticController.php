<?php
namespace operate\controllers;

use Yii;
use operate\models\Logistic;
use backend\components\BackendController;
class LogisticController extends BackendController{
	public $layout = false;
	/**
	 * action for domestic logistic list 
	 * @return [type] [description]
	 */
	public function actionDomestic(){
		list($data,$pages,$count) = Logistic::getLogisticData(Yii::$app->request->getQueryParams());
	    return $this->render('domestic', [
	         'results' => $data,
	         'pages' => $pages,
	         'params'=>Yii::$app->request->getQueryParams(),
	         'count'=>$count,
	    ]);
	}
	
	/**
	 * action print 
	 * @return [type] [description]
	 */
	public function actionPrint(){
		$data = Logistic::getPrintData(Yii::$app->request->getQueryParams());
		return $this->renderPartial('print', ['results'=>$data]);
	}
}