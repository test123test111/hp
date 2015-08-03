<?php
namespace console\controllers;
use Yii;
use yii\console\Controller;
use backend\models\Order;
use common\models\Approval;
use backend\models\StockTotal;
class CancelOrderController extends Controller{
    /**
     * push user unread message
     * record the last dateline
     * request push api 
     */
    public function actionRun(){
        while (true) {
            $task = $this->getTasks();
            if (empty($task)) {
                sleep(5);
            }else{
                echo $task->id;echo "\r\n";

                Approval::deleteAll(['order_id'=>$task->id]);
                foreach($task->details as $value){
                    $stockTotal = StockTotal::find()->where(['material_id'=>$value->material_id,'storeroom_id'=>$value->storeroom_id])->one();
                    $num = 0 - $value->quantity;
                    $stockTotal->updateCounters(['lock_num' => $num]);
                }
                $task->is_del = Order::ORDER_IS_DEL;
                $task->status = Order::ORDER_STATUS_IS_CANCEL;
                if($task->update(false)){
                    echo 0;
                }
            }
        }
    }
    /**
     * get tasks from database
     *
     * @param $num:
     *
     * @return
     */
    public function getTasks() {
        return Order::getNeedCancel();
    }
}