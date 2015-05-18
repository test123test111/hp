<?php
namespace backend\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
class OrderLog extends ActiveRecord {
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'order_log';
    }
    /**
     * save log
     * @param  object order
     * @return [type] [description]
     */
    public static function saveLog($order,$log, $operator = 'user', $operatorId = 0, $order_type = 0){
        $order_log = new static();
        $order_log->order_id = $order->id;
        $order_log->log = $log;
        $order_log->user_id = $order->user_id;
        $order_log->create_time = time();
        $order_log->op_type = $order->status;
        $order_log->operator = $operator;
        $order_log->operator_id = $operatorId;
        $order_log->order_type = $order_type;
        $order_log->save();
    }
}