<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\models\Manager;
use backend\models\Logistic;
use backend\models\Order;
use backend\models\Box;
use backend\models\BoxOrder;
use backend\models\StorageLog;
use backend\models\WaitOutput;
use backend\models\search\ManagerSearch;
use backend\components\BackendController;

class OutputController extends BackendController{
	/**
	 * display output first page
	 * default is printed box
	 * @return [type] [description]
	 */
	public function actionIndex(){
		$status = Yii::$app->request->get('st');
		$boxes = Box::getNeedSendBox($status);
		return $this->render('index',['boxes'=>$boxes,'status'=>$status]);
	}
	/**
	 * action for update need output box
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionUpdate($id){
		$box_id = Yii::$app->request->get('id');
		$box = Box::findOne($box_id);
		if(empty($box) || ($box->status != Box::BOX_STATUS_IN && $box->status != Box::BOX_STATUS_PRINTED)){
			throw new \Exception("Error Processing Request", 404);
		}
		$not_send_order_count = $box->getNotSendOrderCount();
		WaitOutput::deleteAll(['box_id'=>$box_id]);
		return $this->render('update',['box'=>$box,'count'=>$not_send_order_count]);
	}
	/**
	 * action for valid box 
	 * @return [type] [description]
	 */
	public function actionValid(){
		if(Yii::$app->request->isPost){
			$box_id = Yii::$app->request->post('box_id');
			$box = Box::findOne($box_id);
			if(empty($box) || ($box->status != Box::BOX_STATUS_IN && $box->status != Box::BOX_STATUS_PRINTED)){
				echo $this->sendResponse(['rst'=>"",'errno'=>Box::BOX_IS_NOT_EXIST,'err'=>'您所输入的物流箱不属于可编辑状态，或者物流箱不存在，请重新再试']); 
			}else{
				echo $this->sendResponse(['rst'=>"/output/update/{$box_id}",'errno'=>Box::REQUEST_IS_SUCCESS,'err'=>'']); 
			}
		}
	}
	/**
	 * scan express no 
	 * check this express no is already output or not
	 * @return [type] [description]
	 */
	public function actionScanexpress(){
		if(Yii::$app->request->isPost){
			$data = "";
			$express_no = Yii::$app->request->post('express_no');
			if(strlen(trim($express_no)) < 12){
				echo $this->sendResponse(['rst'=>$data,'errno'=>Box::EXPRESS_NUMBER_ERROR,'err'=>"请扫描正确的快递单号"]); 
			}else{
				list($result, $return_string, $req_type) = Box::validateExpress($express_no);
				if($req_type == Box::REQUEST_IS_SUCCESS){
					$data = $this->renderPartial('express', [
						'result' => $result,
						'express_no'=>$express_no,
						'express_list'=>Yii::$app->params['express'],
					]);
				}
				echo $this->sendResponse(['rst'=>$data,'errno'=>$req_type,'err'=>$return_string]);
			}
			
		}
	}
	/**
	 * scan order 
	 * check order is in this box 
	 * check order is in other box
	 * check box have the same address order
	 * @return [type] [description]
	 */
	public function actionScanorder(){
		if(Yii::$app->request->isPost){
			$order_id = Yii::$app->request->post('order_id');
			$box_id = Yii::$app->request->post('box_id');
			$express_no = Yii::$app->request->post('express_no');
			if(Yii::$app->request->post('direct') !== null){
				list($result, $return_string, $req_type) = Order::checkOrderForOutput($box_id,$order_id,$express_no,true);
			}else{
				list($result, $return_string, $req_type) = Order::checkOrderForOutput($box_id,$order_id,$express_no);
			}
			$data = [];
			if($req_type == Box::REQUEST_IS_SUCCESS){
				$order_info = $this->renderPartial('order',['result'=>$result['order'],'same_orders'=>$result['same_orders']]);
				$data['order'] = $order_info;
			}
			echo $this->sendResponse(['rst'=>$data,'errno'=>$req_type,'err'=>$return_string]);
		}
	}
	/**
	 * delivery of cargo from storage
	 * add a new record in table logistic
	 * update storage status,logistic_id,out_time
	 * save log
	 * send Notification
	 * logistic tracking
	 * @return [type] [description]
	 */
	public function actionDo(){
		if(Yii::$app->request->isPost){
			$order_ids = Yii::$app->request->post('order_id');
			$box_id = Yii::$app->request->post('box_id');
			$express_no = Yii::$app->request->post('express_no');
			$logistic = Yii::$app->request->post('logistic_provider');
			if($express_no == null || $order_ids == null){
				echo $this->sendResponse(['rst'=>"",'errno'=>Box::OUTPUT_ERROR,'err'=>"快递单号或者订单号不能为空"]);
				Yii::$app->end();
			}
			if(isset(Yii::$app->params['express'][$logistic])){
				$logistic_provider = Yii::$app->params['express'][$logistic];
			}else{
				echo $this->sendResponse(['rst'=>"",'errno'=>Box::EXPRESS_ERROR,'err'=>"快递公司不存在"]);
				Yii::$app->end();
			}
			
			foreach($order_ids as $id){
				$logistic = Logistic::saveLogistic($id,$express_no,$logistic_provider,$box_id);
				StorageLog::saveLog($box_id,$id,'出库','已出库',$logistic_provider.":".$express_no);
			}
			$box = Box::findOne($box_id);
			$not_send_order_count = $box->getNotSendOrderCount();

			if($not_send_order_count == 0){
				$box->status = Box::BOX_STATUS_OUT;
				$box->save();
				echo $this->sendResponse(['rst'=>"/output/index",'errno'=>Box::REQUEST_IS_SUCCESS,'err'=>"出库成功"]);
			}else{
				echo $this->sendResponse(['rst'=>"/output/update/{$box_id}",'errno'=>Box::REQUEST_IS_SUCCESS,'err'=>"出库失败"]);
			}
		}
	}
	/*
	 * 将订单从出库列表中移出，只是页面上移出，再从wait_out表中删除记录
	 */
	public function actionRmorder(){
		if(Yii::$app->request->isPost) {
			$order_id = Yii::$app->request->post('order_id');
			$box_id = Yii::$app->request->post('box_id');
			WaitOutput::deleteAll(['box_id'=>$box_id,'order_id'=>$order_id]);
			echo $this->sendResponse(['rst'=>[],'errno'=>Box::REQUEST_IS_SUCCESS,'err'=>[]]);
		}
	}
}
