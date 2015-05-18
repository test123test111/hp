<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use backend\models\Order;
use backend\models\Payment;
/**
 * Class useraddr
 * @package common\models
 */
class UserRefund extends ActiveRecord
{
	const STATUS_UNHANDLE = 0;
	const STATUS_SUCC = 2;
	const STATUS_FAIL = 4;
	const REBACK_BY_SOURCE = 0;
	const REFUND_IS_FULL = 0;
	const REFUND_IS_PART = 1;

    const PER_PAGE_DISPLAY_NUM = 30;
    /**
     * set table name
     * @return string
     */
    public static function tableName()
    {
        return 'user_refund';
    }
    /**
     * table order and user_refund relationship
     * @return [type] [description]
     */
    public function getOrders(){
        return $this->hasOne(Order::className(),['id'=>'order_id']);
    }
    /**
     * @param $price
     * @param $payType
     * @return float
     */
    public static function countPrepay($price, $payType=Order::PRE_PAY) {
        if($payType == Order::ALL_PAY){
            return round($price , 2);
        }else{
            return round($price * PREPAY_RATIO, 2);
        }
    }

    /**
     * get refund data
     * @return [type] [description]
     */
    public static function getRefundData($params){
        $query = static::find()->with(['orders'=>function($query){
                                return $query->with(['stocks','stockAmount']);
                            }])
                            ->orderBy(['create_time'=>SORT_DESC]);
        if(isset($params['status'])){
            if($params['status'] != 'all'){
                $query->where(['status'=>$params['status']]);
            }
        }else{
            $query->where(['status'=>self::STATUS_UNHANDLE]);
        }
        if(isset($params['order_id']) && $params['order_id'] != ""){
            $query->andWhere(['order_id'=>$params['order_id']]);
        }
        $count = $query->count();
        $pages = new \yii\data\Pagination(['totalCount' => $count]);
        $ret = [];
        $query->offset($pages->offset)->limit(self::PER_PAGE_DISPLAY_NUM);

        $data = $query->all();
        return [$data,$pages,$count];
    }
    /**
     * reject user refund
     * @return [type] [description]
     */
    public function reject(){
        $this->status = self::STATUS_FAIL;
        if($this->update()){
            return true;
        }
        return false;
    }
    /**
     * [getNeedRefundAmount description]
     * @return [type] [description]
     */
    public function getNeedRefundAmount(){
        if($this->range == self::REFUND_IS_PART) {
            return $this->amount;
        } else {
            $payments = Payment::find()->where(['id'=>$this->orders->pre_payment_id])->all();
            $retStr = 0;
            foreach($payments as $payment) {
                if($payment->status == 'payed') {
                    $retStr += $payment->amount;
                }
            }
            $retStr = $this->orders->sum_price >= $retStr ? $retStr : $this->orders->sum_price;
            return sprintf("%.2f", $retStr);
        }
    }
    /**
     * [getOrderImg description]
     * @return [type] [description]
     */
    public function getOrderImg(){
        $imgs = json_decode($this->orders->stocks->imgs,true);
        return $imgs[0];
    }
    /**
     * get all refund ids for batch refund 
     * @return [type] [description]
     */
    public static function getAllNeedRefund(){
        $query = static::find()->where(['status'=>self::STATUS_UNHANDLE]);
        $arr = [];
        $results = $query->batch();
        foreach($results as $v){
            foreach($v as $v1){
                array_push($arr, $v1['id']);
            }
        }   
        return $arr;          
    }

}