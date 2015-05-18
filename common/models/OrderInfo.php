<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
/**
 * Class Order info
 * @package common\models
 */
class OrderInfo extends ActiveRecord
{

	const STATUS_NO_PAY = 0;
	const STATUS_PAYMENT_TIMEOUT = 1;
	const STATUS_USER_CANCEL = 2;
	const STATUS_PAYMENT_SUCCESS = 3;
	const STATUS_STOCK_SUCCESS = 4;
	const STATUS_STOCK_FAIL = 5;
	const STATUS_BUYER_SEND = 6;
	const STATUS_STORAGE = 7;
	const STATUS_OUTBOUN = 8;
	const STATUS_DELIVERY = 9;
	const STATUS_CONFIRM_RECEIVED = 10;
    const STATUS_BUYER_CANCEL = 11;


    /**
     * set table name
     * @return string
     */
    public static function tableName()
    {
        return 'am_order_info';
    }
    public function behaviors()
    {
        return BaseArrayHelper::merge(
            parent::behaviors(),
            [
                'timestamp' => [
                    'class' => 'yii\behaviors\TimestampBehavior',
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created_time', 'modified_time'],
                    ],
                ],
           ]
        );
    }
    /**
     * update order status
     * @param  int status
     * @return true|false
     */
    private function updateStatus($status){
        $this->status = $status;
        $this->modified_time = time();
        if(!$this->update()){
            return false;
        }
        return true;
    }
    /**
     * set order status is cancel
     * @param  int status
     * @return true|false
     */
    public function setOrderCancel(){
        return $this->updateStatus(self::STATUS_USER_CANCEL);
    }
    /**
     * unlocked stock amount 
     * @return [type] [description]
     */
    public function unlockedStock(){
        $stockAmount = StockAmount::findOne($this->stock_amount_id);
        $stockAmount->locked_amount = $stockAmount->locked_amount - $this->goods_qty;
        return $stockAmount->save();
    }
    
}