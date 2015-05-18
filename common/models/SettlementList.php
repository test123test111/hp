<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use backend\models\Order;
class SettlementList extends ActiveRecord
{
    const STATUS_IS_SETTLEMENT = 1;
    const STATUS_IS_NOT_SETTLEMENT = 0;
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'settlement_list';
    }
    /**
     * table delivery_abroad and settlement_list relationship
     */
    public function getDelivery(){
    	return $this->hasOne(Delivery::className(),['order_id'=>'order_id']);
    }
    /**
     * table order and settlement_list relationship
     * @return [type] [description]
     */
    public function getOrders(){
    	return $this->hasOne(Order::className(),['id'=>'order_id']);
    }
}
