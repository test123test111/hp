<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\models\Box;
use backend\models\Printer;
use backend\models\search\BoxSearch;
use backend\components\BackendController;
use backend\models\AssignMent;

class InputController extends BackendController{
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
	 * update box 
	 * if box status is created only the box's owner can update
	 * @return [type] [description]
	 */
	public function actionUpdate($id){
		$box = Box::findOne($id);
		if(empty($box)){
			throw new \Exception('The requested page does not exist.',404);
		}
		if($box->status == Box::BOX_STATUS_CREATED){
			if($box->created_uid != Yii::$app->user->id && AssignMent::CheckUserIsAdmin(Yii::$app->user->id) == false){
				Yii::$app->session->setFlash('error', '只有包装箱创建人才能编辑');
				return $this->render('update',['box'=>[]]);
			}
		}
		return $this->render('update',['box'=>$box->getBoxDetail()]);
	}
	/**
	 * action for print box barcode
	 * only need to save in redis 
	 * @return [type] [description]
	 */
    public function actionPrint(){  
        if(Yii::$app->request->isPost){
			$box_id = Yii::$app->request->post('box_id');
			$key = Yii::$app->params['box_print_redis_key'];
			$print_type = Printer::TYPE_IS_BOX;
			$printer_id = Printer::getPrinterIdByTypeAndUser($print_type,Yii::$app->user->id);
			$key .= $printer_id;
        	Yii::$app->redis->executeCommand('LPUSH',[$key,$box_id]);

        	echo $this->sendResponse(['rst'=>"",'errno'=>Box::REQUEST_IS_SUCCESS,'err'=>'success']);
		}
	}
	/**
	 * action for create box
	 * @return [type] [description]
	 */
	public function actionCreate(){
		if(Yii::$app->request->isPost){
			$model = new Box();
	        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
	        	$model->save();
	        	//save data in redis for print
	        	$key = Yii::$app->params['box_print_redis_key'];
				$print_type = Printer::TYPE_IS_BOX;
				$printer_id = Printer::getPrinterIdByTypeAndUser($print_type,Yii::$app->user->id);
				$key .= $printer_id;
	        	Yii::$app->redis->executeCommand('LPUSH',[$key,$model->id]);
	            return $this->redirect('/input/update/'.$model->id);
	        }
		}
	}
	/**
	 * action for seal box
	 * update box status is 1
	 * @return [type] [description]
	 */
	public function actionSealbox(){
		if(Yii::$app->request->isPost){
			$box_id = Yii::$app->request->post('box_id');
			$box = Box::findOne($box_id);
			if(!empty($box)){
				$box->status = Box::BOX_STATUS_IN;
				if($box->save(false) && $box->demostic()){
					return $this->redirect('/input/index');
				}
			}
		}
		return $this->refresh();
	}
	/**
	 * valid box can edit
	 * @return [type] [description]
	 */
	public function actionValid(){
		if(Yii::$app->request->isPost){
			$box_id = Yii::$app->request->post('box_id');
			$box = Box::findOne($box_id);
			if(empty($box) || $box->status != Box::BOX_STATUS_CREATED){
				echo $this->sendResponse(['rst'=>"",'errno'=>Box::BOX_IS_NOT_EXIST,'err'=>'您所输入的物流箱不属于可编辑状态，或者物流箱不存在，请重新再试']); 
				Yii::$app->end();
			}
			if($box->created_uid != Yii::$app->user->id && AssignMent::CheckUserIsAdmin(Yii::$app->user->id) == false){
				echo $this->sendResponse(['rst'=>"",'errno'=>Box::BOX_OWNER_ERROR,'err'=>'只有包装箱创建人才能编辑']); 
				Yii::$app->end();
			}
			echo $this->sendResponse(['rst'=>"/input/update/{$box_id}",'errno'=>Box::REQUEST_IS_SUCCESS,'err'=>'']); 
		}
	}
	/**
	 * action for add order in box
	 * this request is from ajax
	 * post date must include <input type="hidden" value="<?= $request->getCsrfToken(); ?>" name="<?= $request->csrfParam;  ?>">
	 * avoid post data from other channel
	 * @return [type] [description]
	 */
	public function actionAddorder(){
		if(Yii::$app->request->isPost){
			$order_id = Yii::$app->request->post('order_id');
			$box_id = Yii::$app->request->post('box_id');

			list($result, $return_string, $req_type) = Box::putOrderInBox($box_id,$order_id);
			if($req_type != Box::REQUEST_IS_SUCCESS){
				echo $this->sendResponse(['rst'=>"",'errno'=>$req_type,'err'=>$return_string]);
			}else{
				$data = $this->renderPartial('orderlist', ['result' => $result]);
				echo $this->sendResponse(['rst'=>$data,'errno'=>$req_type,'err'=>$return_string]);
			}
		}
		// return $this->render('create');
	}
	/**
	 * action for delete order from a box
	 * @return [type] [description]
	 */
	public function actionDeleteorder(){
		if(Yii::$app->request->isPost){
			$order_id = Yii::$app->request->post('order_id');
			$box_id = Yii::$app->request->post('box_id');
			list($return_string, $req_type) = Box::deleteOrderFromBox($box_id,$order_id);
			echo $this->sendResponse(['rst'=>"",'error'=>$req_type,'err'=>$return_string]);
		}
	}
}