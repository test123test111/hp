<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class SendEmail extends ActiveRecord
{
    const STATUS_IS_SEND = 1;
    const STATUS_IS_NOT_SEND = 0;
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'send_email';
    }
    /**
     * [getNeedSendEmail description]
     * @return [type] [description]
     */
    public static function getNeedSendEmail(){
        return static::find()->where(['status'=>self::STATUS_IS_NOT_SEND])->one();
    }
}
