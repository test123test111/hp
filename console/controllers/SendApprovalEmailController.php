<?php
namespace console\controllers;
use Yii;
use yii\console\Controller;
use common\models\Approval;
use backend\models\Owner;
use backend\models\Order;
class SendApprovalEmailController extends Controller{
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
                $owner = Owner::findOne($task->owner_id);
                $order = Order::findOne($task->order_id);
                if(!empty($owner)){
                    Yii::$app->mail->compose('@customer/views/mail/approval',['record'=>$task,'order'=>$order])
                         ->setFrom('service@yt-logistics.cn')
                         ->setTo($owner->email)
                         ->setSubject("订单审批申请")
                         ->send();
                    $task->send_email = Approval::SEND_EMAIL;
                    $task->update();
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
        return Approval::getNeedSendEmail();
    }
}