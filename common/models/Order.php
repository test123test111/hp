<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\components\AHelper;
use yii\helpers\BaseArrayHelper;
/**
 * Class Order
 * @package common\models
 */
class Order extends ActiveRecord
{
    const ORDER_SOURCE_IOS = 0;
    const ORDER_SOURCE_ANDROID = 1;

    const ORDER_STATUS_NOT_PAY = 0;
    const ORDER_STATUS_IN_PAYMENT = 1;
    const ORDER_STATUS_PAYMENT_SUCC = 2;
    const ORDER_STATUS_CANCEL = 3;

    const ORDER_IS_MULTIPLE = 1;
    const ORDER_IS_NOT_MULTIPLE = 0;
    /**
     * set table name
     * @return string
     */
    public static function tableName()
    {
        return 'am_order';
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
     * create order
     * write into table order order_info order_address
     * @param $items [
     *           'Goods'=>[
     *               'stock_id'=>1,'sku'=>'xxxx','qty'=>1,'note'=>'something'
     *               'stock_id'=>2,'sku'=>'xxxxx','qty'=>2,'note'=>'something'
     *            ],
     *           'Address'=>5,
     *           'buyer_id',
     *        ]
     * @param intval $uid
     * @param int $source
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    protected function createOrder($items, $uid, $source = self::ORDER_SOURCE_IOS)
    {
        $db = self::getDb();
        $transaction = $db->beginTransaction();
        try {
            //create order
            $order = new Static;
            $order->user_id = $uid;
            $order->sn = AHelper::generateOrderId();
            $order->buyer_id = $items['Buyer_id'];
            $order->source = $source;

            $goodsData = $this->getDataByGoods($items['Goods']);
            if($goodsData === false){
                throw new \Exception('商品已下架或库存不足', 12315);
            }
            list($total_price,$goods_info) = $goodsData;
            $order->original_price = round($total_price,2);
            $order->pay_price = round($total_price,2);
            if (!$order->save(false)) {
                throw new \Exception('订单创建失败', 12319);
            }
            //create order info
            if($order->createOrderInfo($goods_info) === false){
                throw new \Exception('订单创建失败', 12320);
            }
            //locked stock amount
            if($this->updateStockAmount($goods_info) === false){
                throw new \Exception('更新商品库存失败', 12321);
            }
            //create order address
            if($order->createOrderAddress($items['Address'],$uid) === false){
                throw new \Exception('订单地址保存失败', 12322);
            }

            $transaction->commit();
            return $order;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
    /**
     * cancel order
     * update order status,stock_amount
     * reback user coupon
     * @todo
     * @return [type] [description]
     */
    public function cancelOrder(){
        if($this->status != self::ORDER_STATUS_NOT_PAY || $this->is_multiple == self::ORDER_IS_MULTIPLE){
            throw new \Exception('当前订单状态不能取消', 12316);
        }
        $db = self::getDb();
        $transaction = $db->beginTransaction();
        try {
            if(!$this->updateStatus(self::ORDER_STATUS_CANCEL)){
                throw new \Exception('订单取消失败', 12323);
            }
            $orderInfo = OrderInfo::find()->where(['order_id'=>$this->id])->all();
            foreach ($orderInfo as $info) {
                if(!$info->setOrderCancel()){
                    throw new \Exception('订单取消失败', 12324);
                }
                //stock amount unlocked
                if(!$info->unlockedStock()){
                    throw new \Exception('订单恢复库存失败', 12325);
                }
            }
            //@todo reback user coupon
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new \Exception($e->getMessage(), $e->getCode());
        }
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
     * create order info 
     * @param  array goods_info
     * @return [type]        [description]
     */
    protected function createOrderInfo($goods_info){
        foreach($goods_info as $value){
            $model = new OrderInfo;
            $model->order_id = $this->id;
            $model->live_id = $value['live_id'];
            $model->stock_id = $value['stock_id'];
            $model->goods_qty = $value['qty'];
            $model->goods_name = $value['goods_name'];
            $model->goods_sku = $value['sku'];
            $model->stock_amount_id = $value['stock_amount_id'];
            $model->goods_original_price = $value['goods_original_price'];
            $model->goods_now_price = $value['goods_now_price'];
            $model->note = $value['note'];
            if(!$model->save(false)){
                return false;
            }
        }
        return true;
    }
    /**
     * update stock amount locked amount
     * @param  array goods_info
     * @return true|false
     */
    protected function updateStockAmount($goods_info){
        foreach($goods_info as $goods){
            $stockAmount = StockAmount::findOne($goods['stock_amount_id']);
            if(!empty($stockAmount)){
                if(($stockAmount->updateCounters(['locked_amount' => $goods['qty']])) === false){
                    return false;
                }
            }
        }    
        return true;    
    }
    /**
     * get total price by goods
     * @param  array $goods
     * @return false|object(stock,live),return stock live object for create order info
     */
    protected function getDataByGoods($goods){
        $price = 0;
        $order_info_array = [];
        foreach($goods as $key=>$value){
            if($value['qty'] <= 0){
                return false;
            }
            $stockAmount = StockAmount::findOne($value['stock_amount_id']);
            if(empty($stockAmount)){
                return false;
            }
            if($stockAmount->amount < ($stockAmount->locked_amount + $stockAmount->sold_amount + $value['qty'])){
                return false;
            }
            $liveStock = liveStock::find()->where(['stock_id'=>$stockAmount->stock_id])->one();
            if(empty($liveStock)){
                return false;
            }
            $live = Live::findOne($liveStock->live_id);
            if(empty($live)){
                return false;
            }
            $order_info_array[$key]['live_id'] = $live->id;
            $order_info_array[$key]['stock_id'] = $stockAmount->stock_id;
            $order_info_array[$key]['stock_amount_id'] = $stockAmount->id;
            $order_info_array[$key]['sku'] = $stockAmount->sku_value;
            $order_info_array[$key]['goods_original_price'] = $stockAmount->stocks->priceout;
            $order_info_array[$key]['goods_now_price'] = $stockAmount->stocks->priceout;
            $order_info_array[$key]['qty'] = $value['qty'];
            $order_info_array[$key]['note'] = $value['note'];
            $order_info_array[$key]['goods_name'] = $stockAmount->stocks->name;


            $price += $stockAmount->stocks->priceout * $value['qty'];
        }
        return [$price,$order_info_array];
    }
    /**
     * create order address
     * @param  intval address id
     * @param  int uid
     * @return true|false
     */
    protected function createOrderAddress($address_id,$uid){
        $address = UserAddr::findOne($address_id);
        if(empty($address) || $address->user_id != $uid){
            return false;
        }
        $model = new OrderAddress;
        $model->order_id = $this->id;
        $model->name = $address->name;
        $model->country = $address->country;
        $model->province = $address->province;
        $model->city = $address->city;
        $model->addr = $address->addr;
        $model->postcode = $address->postcode;
        $model->phone = $address->phone;
        $model->cellphone = $address->cellphone;
        if(!$model->save(false)){
            return false;
        }
        return true;

    }
    /**
     * after order pay success update some data,update order status and order info status
     * @return [type] [description]
     */
    public function paySuccess(){

    }
    /**
     * after order pay fail update some data,update order status and order info status
     * @return [type] [description]
     */
    public function payFail(){

    }
    /**
     * create new pay for user payment
     * @return [type] [description]
     */
    public function createNewPay($pay_method){
        $newPay = new Payment;
        $newPay->order_id = $this->id;
        $newPay->status = Payment::STATUS_CREATED;
        $newPay->pay_method = $pay_method;
        $newPay->create_time = time();
        if(!$newPay->save(false)){
            return null;
        }
        return $newPay;
    }
    /**
     * check goods is belong to the same buyer
     * if is not same buyer parse array
     * @param $items
     * @return array
     */
    public function checkBuyer($items)
    {
        $results = [];
        foreach ($items['goods'] as $key => $item) {
            $goods = StockAmount::findOne($item['stock_amount_id']);
            $results['Buyer'][$goods->stocks->buyer_id][$key] = $item;
        }
        unset($items['goods']);
        $new_items = [];
        foreach ($results['Buyer'] as $key => $result) {
            $new_items[$key]['Address'] = $items['address'];
            $new_items[$key]['Preferential'] = isset($items['preferential']) ? $items['preferential'] : [];
            $new_items[$key]['Goods'] = $result;
            $new_items[$key]['Buyer_id'] = $key;
        }
        return $new_items;
    }
    /**
     * create a new order for combined order
     * if user buy some goods from at least two buyer,create a new pay order for go to the paycenter
     * this order is only for pay,price is order[1] + order[2],if user no pay,then go to the order center,this order number
     * will not to see,user need to Separate payment
     * @param $orders
     * @return false|string
     */
    protected function createCombinedOrder($orders)
    {
        $model = new static;
        $model->sn = AHelper::generateOrderId();
        if (!$model->save(false)) {
            return false;
        }
        $original_price = 0;
        $pay_price = 0;
        foreach ($orders as $id) {
            $order = static::findOne(['sn'=>$id]);
            $original_price += $order->original_price;
            $pay_price += $order->pay_price;
            $order->parent_id = $model->id;
            if(!$order->update()){
                return false;
            }
        }
        $model->source = $order->source;
        $model->user_id = $order->user_id;
        $model->original_price = $original_price;
        $model->pay_price = $pay_price;
        $model->source = $order->source;
        $model->is_multiple = self::ORDER_IS_MULTIPLE;
        if (!$model->update()) {
            return false;
        }
        return $model;
    }
    /**
     * create pay order,order,child order from client post data
     * @param  [type] $items [description]
     * @return pay order sn
     */
    public function orderBuy($items,$uid){
        $new_items = $this->checkBuyer($items);
        $order_array = [];
        $db = self::getDb();
        $transaction = $db->beginTransaction();
        try{
            //check stock amount enough
            foreach ($new_items as $item) {
                $order= $this->createOrder($item, $uid);
                array_push($order_array, $order->sn);
            }
            if (count($new_items) > 1) {
                $order = $this->createCombinedOrder($order_array);
            }
            $transaction->commit();
            return $result;
        }catch (\Exception $e) {
            $transaction->rollBack();
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        
    }
    /**
     * get order need pay parice
     * example:user use coupon or goods discount or buyer sale discount 
     * @param  object order
     * @param  array perferential_array example:['coupon'=>['number'=>'xxxxxxx']]
     * @return pay price
     */
    public function getOrderPayPrice($perferential_array){
        if(empty($perferential_array)){
            return $this;
        }
        return Yii::$app->discount->getOrderPayPrice($this,$perferential_array);
    }
}