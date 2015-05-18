<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\models\Box;
use backend\models\Order;
use backend\models\StockOrderDelete;
use backend\components\BackendController;
use backend\models\AssignMent;
use backend\models\StorageLog;
class StockController extends BackendController{

	/**
	 * action for diplay box
	 * status is instock or printed
	 * @return [type] [description]
	 */
	public function actionIndex(){
		$status = Yii::$app->request->get('st');
		$boxes = Box::getInStockBox($status);
		return $this->render('index',['boxes'=>$boxes,'status'=>$status]);
	}
	/**
	 * action for update box 
	 * only status is in or printed can update
	 * @return [type] [description]
	 */
	public function actionUpdate(){
		$box_id = Yii::$app->request->get('id');
		$box = Box::findOne($box_id);
		if(empty($box) || ($box->status != Box::BOX_STATUS_IN && $box->status != Box::BOX_STATUS_PRINTED)){
			throw new \Exception("Error Processing Request", 1);
		}

		// return $this->render('update');
		return $this->render('update', ['boxInfo' => $box->getBoxInfo(),
			'express_list'=>Yii::$app->params['express'],
			'is_admin'=>AssignMent::CheckUserIsAdmin(Yii::$app->user->id),
			'del_reasons'=>StockOrderDelete::getDeleteReasons(),
			]);
	}
	/**
	 * valid box can edit
	 * @return [type] [description]
	 */
	public function actionValid(){
		if(Yii::$app->request->isPost){
			$box_id = Yii::$app->request->post('box_id');
			$box = Box::findOne($box_id);
			if(empty($box) || ($box->status != Box::BOX_STATUS_IN && $box->status != Box::BOX_STATUS_PRINTED)){
				echo $this->sendResponse(['rst'=>"",'errno'=>Box::BOX_IS_NOT_EXIST,'err'=>'您所输入的物流箱不属于可编辑状态，或者物流箱不存在，请重新再试']); 
			}else{
				echo $this->sendResponse(['rst'=>"/stock/update/{$box_id}",'errno'=>Box::REQUEST_IS_SUCCESS,'err'=>'']); 
			}
		}
	}
	/**
	 * action for single print express 
	 * @return [type] [description]
	 */
	public function actionPrint(){
		$order_id = Yii::$app->request->get('orderid');
		$express_id = Yii::$app->request->get('eid');

		$template = Box::getTemplateByExpressId($express_id);
		$users = Order::getNeedExpressData([$order_id]);
		return $this->renderPartial($template,['users'=>$users]);
	}
	/**
	 * action for delete order from box 
	 * order status update to to_demostic
	 * @return [type] [description]
	 */
	public function actionDelete(){
		if(Yii::$app->request->isPost){
			$order_id = Yii::$app->request->post('order_id');
			$box_id = Yii::$app->request->post('box_id');
			$reason = Yii::$app->request->post('reason_id');
			list($return_string, $req_type) = Box::deleteOrderFromBox($box_id,$order_id,true);
			if($req_type == Box::REQUEST_IS_SUCCESS){
				$model = new StockOrderDelete();
				$model->box_id = $box_id;
				$model->order_id = $order_id;
				$model->reason = $reason;
				$model->save();
			}
			StorageLog::saveLog($box_id,$order_id,'删除','国外已发货',StockOrderDelete::getDeleteReasons()[$reason]);
			echo $this->sendResponse(['rst'=>"",'errno'=>$req_type,'err'=>$return_string]);
		}
	}
	/**
	 * action for export data
	 * @return [type] [description]
	 */
	public function actionExport(){
		
	}
	/**
	 * action for batch print order
	 * @return [type] [description]
	 */
	public function actionBatchprint(){
		if(Yii::$app->request->isPost){
			$orderids = Yii::$app->request->post('orderids');
			if(empty($orderids)){
				echo "无效的订单id,请检查选择的快递模版";
				Yii::$app->end();
			}
			$orderids = explode(',', $orderids);
			$express_id = Yii::$app->request->post('eid');
			$template = Box::getTemplateByExpressId($express_id);
			$users = Order::getNeedExpressData($orderids);
			$box_id = Yii::$app->request->post('box_id');
			$box = Box::findOne($box_id);
			if(!empty($box)){
				$box->status = Box::BOX_STATUS_PRINTED;
				$box->save();
			}
			return $this->renderPartial($template,['users'=>$users]);
		}
	}
}