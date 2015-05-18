<?php
namespace finance\controllers;

use Yii;
use yii\filters\AccessControl;
use common\models\UserRefund;
use backend\components\BackendController;
use common\models\OrderNote;
use common\models\WaitRefund;

class RefundController extends BackendController{
    public $layout = false;
    const REQUEST_IS_SUCCESS = 0;
    const REQUEST_IS_FAIL = 10011;
    public function actionDo(){
    	if(Yii::$app->request->isPost){
    		$refund_id = Yii::$app->request->post('id');
	    	list($ret,$msg,$code) = Yii::$app->refund->doRefund($refund_id);
	    	if($ret){
	    		Yii::$app->session->setFlash('success',"退款成功!");
	    	}else{
	    		Yii::$app->session->setFlash('info',$msg);
	    	}
            echo self::REQUEST_IS_SUCCESS;
            Yii::$app->end();
    	}
    	
    }
    /**
     * user refund list
     * @return [type] [description]
     */
    public function actionList(){
    	list($data,$pages,$count) = UserRefund::getRefundData(Yii::$app->request->getQueryParams());
	    return $this->render('list', [
	         'results' => $data,
	         'pages' => $pages,
	         'count'=>$count,
	         'params'=>Yii::$app->request->getQueryParams(),
	    ]);
    }
    /**
     * action for batch refund
     * @return [type] [description]
     */
    public function actionBatch(){
    	if(Yii::$app->request->isPost){
            // $need_refund_ids = UserRefund::getAllNeedRefund();
    		$need_refund_ids = Yii::$app->request->post('ids');
    		WaitRefund::saveWaitRefundIds($need_refund_ids);
			Yii::$app->session->setFlash('success', '已添加退款到队列!');
            echo 0;
    	}
    }
    /**
     * reject order refund request
     * @return [type] [description]
     */
    public function actionReject(){
    	if(Yii::$app->request->isPost){
    		$id = Yii::$app->request->post('id');
	    	$refund = UserRefund::findOne($id);
	    	if($refund->reject()){
	    		Yii::$app->session->setFlash('success', '退款已拒绝');
	    		return $this->redirect('/refund/list');
	    	}	
    	}
    	
    }
    /**
     * action note list
     * @return [type] [description]
     */
    public function actionNotelist(){
        $order_id = Yii::$app->request->post('order_id');
        $results = OrderNote::getNoteListByOrderId($order_id);
        $data = $this->renderPartial('notelist', ['results' => $results,'order_id'=>$order_id]);
        echo $this->sendResponse(['rst'=>$data,'errno'=>self::REQUEST_IS_SUCCESS,'err'=>'']);
    }
    /**
     * create note list
     * @return [type] [description]
     */
    public function actionCreatenote(){
        if(Yii::$app->request->isPost){
            $order_id = Yii::$app->request->post('order_id');
            $content = Yii::$app->request->post('content');
            $result = OrderNote::createNote($order_id,$content,OrderNote::TYPE_IS_MANAGER,'管理员',Yii::$app->user->id);
            if($result){
                $data = $this->renderPartial('newnote',['result'=>$result]);
                echo $this->sendResponse(['rst'=>$data,'errno'=>self::REQUEST_IS_SUCCESS,'err'=>'']);
            }else{
                echo $this->sendResponse(['rst'=>[],'errno'=>self::REQUEST_IS_FAIL,'err'=>'系统错误']);
            }
        }
    }
}