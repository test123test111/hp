<?php
namespace console\controllers;
use Yii;
use amapi\models\PmMessages;
use yii\console\Controller;
use common\components\AHelper;
class PushController extends Controller{
    protected $max = 10;


    const MSG_MARK = 2; 

    const USER_TYPE = 1;
    const BUYER_TYPE = 2;

    protected $_secret = '12344567890abc';

    /**
     * push user unread message
     * record the last dateline
     * request push api 
     */
    public function actionRun(){
        if(file_exists(Yii::$app->params['msg_dateline_file_path'])){
            $dateline = file_get_contents(Yii::$app->params['msg_dateline_file_path']);
        }else{
            $dateline = "";
        }

        while (true) {
            $tasks = $this->getTasks($dateline);
            if (empty($tasks)) {
                sleep(5);
            }else{
                foreach ($tasks as $t) {
                    $to_uid = $t['to_uid'];
                    if($t['to_type'] == 'user'){
                        $to_user_type = self::USER_TYPE;
                    }else{
                        $to_user_type = self::BUYER_TYPE;
                    }
                    $nickname = $t['from_nickname'];
                    $message = "【私信】".$nickname."给您发来一条消息";
                    $dateline = $t['dateline'];
                    $sign = md5($to_user_type.self::MSG_MARK.$this->_secret);
                    $params = json_encode(array(
                            'os'=>'iOS',
                            'id'=>$to_uid,
                            'msg'=>$message,
                            'type'=>$to_user_type,
                            'mark'=>self::MSG_MARK,
                            'auth'=>$sign,
                    ));
                    AHelper::curl_json("data=".$params,Yii::$app->params['push_url']);
                }
                file_put_contents(Yii::$app->params['msg_dateline_file_path'], $dateline);
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
    public function getTasks($dateline,$num = 10) {
        return PmMessages::getNeedPushMsg($dateline,$num);
    }
}