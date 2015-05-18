<?php
namespace common\models\preferential;

use Yii;
use yii\base\Object;
use common\models\Coupon as Coup;
use common\models\Order;
use common\models\OrderInfo;
use common\models\OrderPreferentialRecord;
/**
 * 优惠券折扣
 * Class Coupon
 * @package mall\models\preferential
 */
class Coupon extends Preferential
{
    const COUPON_IS_NOT_VALID = 12317;
    const USE_COUPON_FAIL = 12318;
    /**
     * @var int preferential type
     */
    const TYPE = 1;
    /**
     * @var string preferential note
     */
    const ANNOTATION = '优惠券优惠';

    public $number = "0";

    public $goods_count;
    /**
     * 返回优惠类型
     * @return int|mixed
     */
    public function getPreferentialType()
    {
        return self::TYPE;
    }

    public function getPreferentialOrderType(){
        if($this->_order->is_multiple == Order::ORDER_IS_MULTIPLE){
            return OrderPreferentialRecord::ORDER_TYPE_IS_PAY;
        }
        return OrderPreferentialRecord::ORDER_TYPE_IS_PARENT;
    }
    /**
     * 返回优惠注释
     * @return mixed|string
     */
    public function getPreferentialAnnotation()
    {
        return self::ANNOTATION;
    }


    /**
     * 获取折扣
     * @param $uid int 会员ID
     * @return mixed
     */
    public function getPriceAfterDisount()
    {
        if(($this->_order instanceof \common\models\Order) === false){
            throw new \Exception("bad request");
        }
        $db = Order::getDb();
        $transaction = $db->beginTransaction();
        try {
            $this->before_price = $this->_order->original_price;
            $couponId = $this->number;
            if ($couponId) {
                $coupon = Coup::checkCouponValid($couponId,$this->_order->user_id);
                if($coupon === false){
                    throw new \Exception("优惠券无效",self::COUPON_IS_NOT_VALID);
                }
                $coupon_value = $coupon->value;
                if($this->updatePriceByOrder($coupon_value) === false){
                     throw new \Exception("使用优惠券失败",self::USE_COUPON_FAIL);
                }
                $this->after_price = $this->_order->pay_price;
                $this->content = $couponId;
                $this->insertOrderPreferentialRecord();
            }
            $transaction->commit();
            return $this->_order->pay_price;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
    /**
     * update price by order 
     * if order is pay order need update pay order and parent order and child order
     * if order is not pay order need update order and child order
     * @return [type] [description]
     */
    public function updatePriceByOrder($coupon_price){
        $average_price = round($coupon_price/$this->getGoodsCount(),2);
        return $this->updateAllOrderPrice($coupon_price,$average_price);
    }
    /**
     * update all order price
     * if this order is pay order update pay order,parent order and child order price
     * if this order is parent order only update parent order and child order price
     * @param  [type] $discount_price [description]
     * @return [type]                 [description]
     */
    protected function updateAllOrderPrice($coupon_price,$discount_price){
        $db = Order::getDb();
        $transaction = $db->beginTransaction();
        try{
            if($this->_order->is_multiple == Order::ORDER_IS_MULTIPLE){
                $orders = Order::find()->where(['parent_id'=>$this->_order->id])->all();
                foreach($orders as $order){
                    $childOrders = OrderInfo::find()->where(['order_id'=>$order->id])->all();
                    $parent_need_discount_price = $this->updateChildOrderPrice($order,$childOrders,$discount_price);
                    if($parent_need_discount_price === false){
                        return false;
                    }
                }
                $this->_order->pay_price = $this->_order->original_price - $coupon_price;
                if($this->_order->update() === false){
                    return false;
                }
            }else{
                $childOrders = OrderInfo::find()->where(['order_id'=>$this->_order->id])->all();
                $this->updateChildOrderPrice($this->_order,$childOrders,$discount_price);
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
        
    }
    /**
     * get all goods num count by order
     * @return [type] [description]
     */
    protected function getGoodsCount(){
        $goods_count = 0;
        if($this->_order->is_multiple == Order::ORDER_IS_MULTIPLE){
            $orders = Order::find()->where(['parent_id'=>$this->_order->id])->all();
            foreach($orders as $order){
                $childOrders = OrderInfo::find()->where(['order_id'=>$order->id])->all();
                $goods_count += $this->getChildGoodsCount($childOrders);
            }
        }else{
            $childOrders = OrderInfo::find()->where(['order_id'=>$this->_order->id])->all();
            $goods_count += $this->getChildGoodsCount($childOrders);
        }
        return $goods_count;
    }
    /**
     * get goods number count by parent order id
     * @return [type] [description]
     */
    public function getChildGoodsCount($childOrders){
        $count = 0;
        foreach($childOrders as $childOrder){
            if($childOrder->goods_original_price > 0){
                $count += $childOrder->goods_qty;
            }
        }
        return $count;
    }

 
}