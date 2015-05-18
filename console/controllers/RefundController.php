<?php
namespace console\controllers;
use Yii;
use common\models\WaitRefund;
use yii\console\Controller;
class RefundController extends Controller{
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
                $refund_id = $task->user_refund_id;
                echo $refund_id;echo "\r\n";
                list($ret,$msg,$code) = Yii::$app->refund->doRefund($refund_id);
                $task->response_code = $ret;
                $task->status = WaitRefund::STATUS_IS_HANDLE;
                $task->response_msg = $msg;
                $task->updated_time = time();
                $task->update();
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
        return WaitRefund::getNeedRefundId();
    }
}