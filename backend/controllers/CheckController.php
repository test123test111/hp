<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\models\Pack;
use backend\models\Printer;
use backend\models\Box;
use backend\components\BackendController;
use backend\models\AssignMent;

class CheckController extends BackendController{
	/**
	 * display first page
	 * @return [type] [description]
	 */
	public function actionIndex(){
		$uid = Yii::$app->user->id;
		$boxes = Box::getEmptyBoxByUid($uid,AssignMent::CheckUserIsAdmin($uid));
		return $this->render('index',['boxes'=>$boxes]);
	}
	/**
	 * scan package display orders
	 * @return [type] [description]
	 */
	public function actionScanpack(){
		if(Yii::$app->request->isPost){
			$pack_id = Yii::$app->request->post('pack_id');
			if(empty($pack_id)){
				echo $this->sendResponse(['rst'=>"",'errno'=>Pack::PACK_IS_NOT_EXIST,'err'=>'扫描的包裹不存在,请检查后重试']);
				Yii::$app->end();
			}
			$pack = Pack::find()->where(['logistic_no'=>$pack_id])->one();
			if(empty($pack)){
				$pack = Pack::findOne($pack_id);
			}
			if(empty($pack)){
				echo $this->sendResponse(['rst'=>"",'errno'=>Pack::PACK_IS_NOT_EXIST,'err'=>'扫描的包裹不存在,请检查后重试']);
				Yii::$app->end();
			}
			$result = $pack->getOrders();
			$data = $this->renderPartial('orderlist', ['result' => $result]);
			echo $this->sendResponse(['rst'=>$data,'error'=>Box::REQUEST_IS_SUCCESS,'err'=>'success']);
		}
	}
	/**
	 * action for print order id barcode
	 * @return [type] [description]
	 */
	public function actionPrint(){
		if(Yii::$app->request->isPost){
			$order_id = Yii::$app->request->post('order_id');
			$key = Yii::$app->params['order_print_redis_key'];
			$print_type = Printer::TYPE_IS_ORDER;
			$printer_id = Printer::getPrinterIdByTypeAndUser($print_type,Yii::$app->user->id);
			$key .= $printer_id;
        	Yii::$app->redis->executeCommand('LPUSH',[$key,$order_id]);

        	echo $this->sendResponse(['rst'=>"",'errno'=>Box::REQUEST_IS_SUCCESS,'err'=>'success']);
		}
	}
	/**
	 * action for batch print order ids
	 * @return [type] [description]
	 */
	public function actionBatchprint(){
		if(Yii::$app->request->isPost){
			$orderids = Yii::$app->request->post('orderids');
			if(empty($orderids)){
				echo $this->sendResponse(['rst'=>"",'errno'=>ORDER::ORDER_IDS_EMPTY,'err'=>'请选择要批量打印的条形码']);
				Yii::$app->end();
			}
			$key = Yii::$app->params['order_print_redis_key'];
			$print_type = Printer::TYPE_IS_ORDER;
			$printer_id = Printer::getPrinterIdByTypeAndUser($print_type,Yii::$app->user->id);
			$key .= $printer_id;
	        foreach($orderids as $new_id){
	            Yii::$app->redis->executeCommand('LPUSH',[$key,$new_id]);
	        }
	        echo $this->sendResponse(['rst'=>"",'errno'=>Box::REQUEST_IS_SUCCESS,'err'=>'success']);
		}
	}
	/**
	 * action for batch input
	 * 1.create new box
	 * 2.print box barcode
	 * 3.save data into table box_order
	 * 4.update box status as BOX_STATUS_IN
	 * @return [type] [description]
	 */
	public function actionBatchinput(){
		if(Yii::$app->request->isPost){
			$orderids = Yii::$app->request->post('orderids');
			$box_id = Yii::$app->request->post('box_id');
			if(empty($orderids)){
				echo $this->sendResponse(['rst'=>"",'errno'=>ORDER::ORDER_IDS_EMPTY,'err'=>'请选择要批量入库的订单']);
				Yii::$app->end();
			}
			list($result, $return_string, $req_type) = Pack::inputOrders($orderids,$box_id);
			echo $this->sendResponse(['rst'=>$result,'errno'=>$req_type,'err'=>$return_string]);
		}
	}
}