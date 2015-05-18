<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * Class payment
 * @package common\models
 */
class IgnoreBuyer extends ActiveRecord
{
    const BUYER_IS_IGNORE = 0;
    const BUYER_IS_NOT_IGNORE = 1;
    /**
     * set table name
     * @return string
     */
    public static function tableName()
    {
        return 'settlement_ignore_buyer';
    }
    /**
     * ignore buyer 
     * @param  [type] $buyer_id [description]
     * @return [type]           [description]
     */
    public static function ignorebuyer($buyer_id){
        $ignore = static::find()->where(['buyer_id'=>$buyer_id])->one();
        if(empty($ignore)){
            $model = new static;
            $model->buyer_id = $buyer_id;
            $model->created_uid = Yii::$app->user->id;
            $model->created_time = time();
            $model->updated_time = time();
            $model->save();
        }else{
            if($ignore->is_ignore == self::BUYER_IS_NOT_IGNORE){
                $ignore->is_ignore = self::BUYER_IS_IGNORE;
                $ignore->updated_time = time();
                $ignore->update();
            }
        }
        return true;
    }
}