<?php
namespace console\controllers;
use Yii;
use yii\console\Controller;
use common\models\Approval;
use backend\models\Owner;
use backend\models\Order;
use common\models\SendEmail;
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
                $user = Owner::findOne($task->created_uid);
                $order = Order::findOne($task->applicant);
                if(!empty($owner)){
                    Yii::$app->mail->compose('@customer/views/mail/approval',[
                                            'record'=>$task,
                                            'order'=>$order,
                                            'email'=>$owner->email,
                                            'username'=>$user->english_name,
                                    ])
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
     * send create user stock warning fee warning 
     * @return [type] [description]
     */
    public function actionRun2(){
        while (true) {
            $task = $this->getNeedSendOtherEmail();
            if (empty($task)) {
                sleep(5);
            }else{
                echo $task->id;echo "\r\n";
                $record = json_decode($task->content,true);
                if($task->template == 'createuser'){
                    $subject = '新用户创建';
                }elseif($task->template == 'stock'){
                    $subject = '库存预警';
                }elseif($task->template == 'warning'){
                    $subject = '费用预警';
                }elseif($task->template == 'newstock'){
                    $subject = '物料入库';
                }
                $template = '@customer/views/mail/'.$task->template;
                if(!empty($record)){
                    Yii::$app->mail->compose($template,[
                                            'record'=>$record,
                                    ])
                         ->setFrom('service@yt-logistics.cn')
                         ->setTo($record['email'])
                         ->setSubject($subject)
                         ->send();
                    $task->status = SendEmail::STATUS_IS_SEND;
                    $task->sendtime = time();
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
    /**
     * get tasks from database
     *
     * @param $num:
     *
     * @return
     */
    public function getNeedSendOtherEmail() {
        return SendEmail::getNeedSendEmail();
    }
}