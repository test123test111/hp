<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * Class useraddr
 * @package common\models
 */
class WaitRefund extends ActiveRecord
{
	const STATUS_IS_UNHANDLE = 0;
	const STATUS_IS_HANDLE = 1;
    /**
     * set table name
     * @return string
     */
    public static function tableName()
    {
        return 'wait_refund';
    }
    /**
     * save need wait refund ids
     * @return [type] [description]
     */
    public static function saveWaitRefundIds($ids){
    	if(!empty($ids)){
    		foreach($ids as $id){
	    		$refund = static::find()->where(['user_refund_id'=>$id])->one();
	    		if(empty($refund)){
	    			$model = new Static;
	    			$model->user_refund_id = $id;
	    			$model->created_time = time();
	    			$model->updated_time = time();
	    			$model->save();
	    		}
	    	}
    	}
    	return true;
    }
    /**
     * get need refund id 
     * @return [type] [description]
     */
    public static function getNeedRefundId(){
        return static::find()->where(['status'=>self::STATUS_IS_UNHANDLE])->one();
    }
}