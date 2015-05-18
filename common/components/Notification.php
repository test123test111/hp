<?php
namespace common\components;
use common\models\User;

class Notification{

    public function sendNotification($user_id,$infos,$isSms=1){
        //TODO
        $ret = User::findOne($user_id);
        if(empty($ret)){
            return false;
        }
        Easemob::getInstance()->sendMsg(
            $ret->easemob_username,
            $infos['title'],
            $infos['type']?$infos['type']:"admin",
            $infos['from']?$infos['from']:"admin",
            $infos['data']?$infos['data']:[]
        );
        // if($isSms){
        //     PhoneUtil::sendSMS($user->mPhone, $infos['title']);
        // }
        return true;
    }
}
