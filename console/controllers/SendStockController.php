<?php
namespace console\controllers;
use Yii;
use yii\console\Controller;
use common\models\Approval;
use backend\models\Stock;
use common\models\SendEmail;
use backend\models\Share;
use backend\models\Hhg;

class SendStockController extends Controller{
    /**
     * push user unread message
     * record the last dateline
     * request push api 
     */
    public function actionRun(){
        $tasks = $this->getTasks();
        if (empty($tasks)) {
            return true;
        }
        $record = [];
        foreach($tasks as $owner_id => $task){
            $subject = '物料入库';
            $template = '@customer/views/mail/newstock';
            foreach($task as $key=>$value){
                $countshare = Share::find()->where(['owner_id'=>$value->owner_id,'status'=>Share::STATUS_IS_NORMAL])->count();
                if($countshare > 1){
                    $share = "已分享";
                }else{
                    $share = "未分享";
                }

                $record[$key]['email'] = $value->owners->email;
                $record[$key]['code'] = $value->material->code;
                $record[$key]['name'] = $value->material->name;
                $record[$key]['stock_time'] = $value->stock_time;
                $record[$key]['quantity'] = $value->actual_quantity;
                $record[$key]['property'] = $value->material->getMyPropertyName();
                $record[$key]['share'] = $share;
                $record[$key]['delivery'] = $value->delivery;
                $record[$key]['info'] = "";
            }
            //send to owner
            if(!empty($record)){
                Yii::$app->mail->compose($template,[
                                        'record'=>$record,
                                ])
                     ->setFrom('service@yt-logistics.cn')
                     ->setTo($record[0]['email'])
                     ->setSubject($subject)
                     ->send();
            }
            //send to hhg
            $hhg = Hhg::find()->all();
            foreach($hhg as $hh){
                Yii::$app->mail->compose($template,[
                                        'record'=>$record,
                                ])
                     ->setFrom('service@yt-logistics.cn')
                     ->setTo($hh->email)
                     ->setSubject($subject)
                     ->send();
            }
        }
        
        
    }

    /**
     * push user unread message
     * record the last dateline
     * request push api 
     */
    public function actionRun2(){
        $tasks = $this->getTomorrowTasks();
        if (empty($tasks)) {
            return true;
        }
        $record = [];
        foreach($tasks as $owner_id => $task){
            $subject = '物料入库';
            $template = '@customer/views/mail/newstock';

            foreach($task as $key=>$value){
                $countshare = Share::find()->where(['owner_id'=>$value->owner_id,'status'=>Share::STATUS_IS_NORMAL])->count();
                if($countshare > 1){
                    $share = "已分享";
                }else{
                    $share = "未分享";
                }

                $record[$key]['email'] = $value->owners->email;
                $record[$key]['code'] = $value->material->code;
                $record[$key]['name'] = $value->material->name;
                $record[$key]['stock_time'] = $value->stock_time;
                $record[$key]['quantity'] = $value->actual_quantity;
                $record[$key]['property'] = $value->material->getMyPropertyName();
                $record[$key]['share'] = $share;
                $record[$key]['delivery'] = $value->delivery;
                $record[$key]['info'] = "";
            }
            //send to owner
            if(!empty($record)){
                Yii::$app->mail->compose($template,[
                                        'record'=>$record,
                                ])
                     ->setFrom('service@yt-logistics.cn')
                     ->setTo($record[0]['email'])
                     ->setSubject($subject)
                     ->send();
            }
            //send to hhg
            $hhg = Hhg::find()->all();
            foreach($hhg as $hh){
                Yii::$app->mail->compose($template,[
                                        'record'=>$record,
                                ])
                     ->setFrom('service@yt-logistics.cn')
                     ->setTo($hh->email)
                     ->setSubject($subject)
                     ->send();
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
        return Stock::getAfternoonNeedSendEmail();
    }

    /**
     * [getTasks description]
     * @return [type] [description]
     */
    public function getTomorrowTasks() {
        return Stock::getTomorrowNeedSendEmail();
    }
}