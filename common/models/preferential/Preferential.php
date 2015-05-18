<?php
namespace common\models\preferential;
use common\models\Order;
use common\models\OrderInfo;
use common\models\OrderPreferentialRecord;

abstract class Preferential
{

    public $before_price;
    public $after_price;
    protected $_order = null;
    public $order_type;
    public $memberId;
    /**
     * from the preferential type discide this content
     * example: prferential is coupon this content is coupon id
     * @var [type]
     */
    public $content;
    /**
     * return preferentail typ
     * @return mixed
     */
    abstract function getPreferentialType();
    /**
     * return preferential annotation
     * @return mixed
     */
    abstract function getPreferentialAnnotation();
    /**
     * get preferential order type :child order or parent order
     * @return mixed
     */
    abstract function getPreferentialOrderType();
    /**
     * get now price after discount
     * @return mixed
     */
    abstract function getPriceAfterDisount();
    /**
     * function set order object
     * @param [type] $order [description]
     */
    public function setOrder($order)
    {
        $this->_order = $order;
        return $this;
    }
    /**
     * set before price
     * @param $value float 
     */
    public function setBeforePrice($value)
    {
        $this->beforePrice = $value;
        return $this;
    }
    /**
     * 设置价格
     * @param $value float 订单价格
     */
    public function getBeforePrice()
    {
        return $this->beforePrice;
    }
    /**
     * set after price
     * @param $value float 
     */
    public function setAfterPrice($value)
    {
        $this->afterPrice = $value;
        return $this;
    }
    /**
     * get after price
     * @param $value float 
     */
    public function getAftertPrice($value)
    {
        return $this->afterPrice;
    }
    /**
     * update order price by order id or type
     * @return [type] [description]
     */
    protected function getAverageDiscountPrice(){
        throw new \Exception('无效操作');
    }
   /**
     * add order discount record
     * @param  int uid 
     * @return mixed|void
     */
    public function insertOrderPreferentialRecord()
    {
        $object = new OrderPreferentialRecord();
        $object->type = $this->getPreferentialType();
        $object->content = $this->content;
        $object->order_type = $this->getPreferentialOrderType();
        $object->annotation = $this->getPreferentialAnnotation();
        $object->order_id = $this->_order->id;
        $object->before_price = $this->before_price;
        $object->after_price = $this->after_price;
        $object->created_time = time();
        if (!$object->save(false)) {
            throw new \Exception($this->getPreferentialAnnotation() . '纪录失败');
        }
    }

    /**
     * update child order price 
     * @return [type] [description]
     */
    protected function updateChildOrderPrice($parent_order,$childOrders,$price){
        $parent_need_discount_price = 0;
        foreach($childOrders as $childOrder){
            if($childOrder->goods_original_price > 0){
                $childOrder->goods_now_price = $childOrder->goods_original_price - ($price * $childOrder->goods_qty);
                if($childOrder->save(false) === false){
                    return false;
                }
                $parent_need_discount_price += $price * $childOrder->goods_qty;
            }
        }
        $parent_order->pay_price = $parent_order->original_price - $parent_need_discount_price;

        if($parent_order->update() === false){
            return false;
        }
        return $parent_need_discount_price;
    }
    /**
     * update parent order price
     * @param  [type] $price [description]
     * @return [type]        [description]
     */
    protected function updateParentOrderPrice($pranet_order,$price){

    }
    /**
     * update pay order price
     */
    protected function updatePayOrderPrice($parent_order){

    }
}