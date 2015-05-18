<?php
namespace backend\models;
use common\models\UserRefund;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
use common\models\Coupon;
use common\models\DeliveryAbroad;
class Order extends ActiveRecord {
    const ORDER_IS_NOT_EXIST = 12001;
    const ORDER_IS_NOT_DEMOSTIC  = 12002;
    const ORDER_IS_NOT_IN_BOX = 12003;
    const BOX_IS_NOT_EXIST = 12004;
    const ORDER_ADDR_NO_COMPARE = 12005;
    const ORDER_IS_SCANED = 12006;
    const ORDER_IDS_EMPTY = 12007;
    const ORDER_IDS_REFUND = 12008;
    const ORDER_STATUS_IS_STOCK = "demostic";
    const ORDER_STATUS_IS_SEND = "to_user";
    const ORDER_STATUS_IS_SUCC = 'success';
    const ORDER_STATUS_IS_REFUND = 'refund';
    const ORDER_STATUS_IS_FULLREFUND = 'full_refund';
    const ORDER_STATUS_IS_WAITREFUND = 'wait_refund';

    const ALL_PAY = 1;
    const PRE_PAY = 0;

    const PREPAY_RATIO = 0.2;

    const ORDER_PAY_TYPE_IS_FULL = 1;
    const ORDER_PAY_TYPE_IS_PART = 0;
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'order';
    }
    /**
     * order and stock relationship
     * @return [type] [description]
     */
    public function getStocks(){
    	return $this->hasOne(Stock::className(),['id'=>'stock_id']);
    }
    /**
     * order and stock_amount relationship
     * @return [type] [description]
     */
    public function getStockAmount(){
    	return $this->hasOne(StockAmount::className(),['id'=>'stock_amount_id']);
    }
    /**
     * table order and box_order relationship
     * @return [type] [description]
     */
    public function getBoxOrders(){
        return $this->hasOne(BoxOrder::className(),['order_id'=>'id']);
    }
    /**
     * table order and payment relationship
     * @return [type] [description]
     */
    public function getPayments(){
        return $this->hasOne(Payment::className(),['id'=>'pre_payment_id']);
    }

    public function getUserRefund(){
        return $this->hasOne(UserRefund::className(),['order_id'=>'id']);
    }
    /**
     * table delivery_abroad and settlement_list relationship
     */
    public function getDelivery(){
        return $this->hasOne(DeliveryAbroad::className(),['order_id'=>'id']);
    }
    /**
     * table pack and order relationship
     * @return [type] [description]
     */
    public function getPack(){
        return $this->hasOne(Pack::className(),['id'=>'pack_id']);
    }
    /**
     * check order for output
     * check order is not empty
     * check order status can output
     * check order is in box,if is not in can transform to this box
     * @param  [type] $box_id   [description]
     * @param  [type] $order_id [description]
     * @return [type]           [description]
     */
    public static function checkOrderForOutput($box_id,$order_id,$express_no,$direct = false){
        $order = static::findOne($order_id);
        $box = Box::findOne($box_id);
        $boxOrder = BoxOrder::find()->where(['order_id'=>$order_id])->one();
        if($direct == false){
            if(empty($order)){
                return ["","对不起，您添加的订单不存在。请检查后重新尝试。",self::ORDER_IS_NOT_EXIST];
            }
            if($order->status == self::ORDER_STATUS_IS_SEND || $order->status == self::ORDER_STATUS_IS_SUCC){
                return ["","该订单已经出库,请勿重复操作。",self::ORDER_IS_NOT_DEMOSTIC];
            }
            if($order->status != self::ORDER_STATUS_IS_STOCK){
                return ["","对不起，您添加的订单不在库。请检查后重新尝试。",self::ORDER_IS_NOT_DEMOSTIC];
            }
            if($order->status == self::ORDER_STATUS_IS_REFUND){
                return ["","对不起，您添加的订单已退定金不允许出库。",self::ORDER_IDS_REFUND];
            }
            if($order->status == self::ORDER_STATUS_IS_FULLREFUND){
                return ["","对不起，您添加的订单已全额退款不允许出库。",self::ORDER_IDS_REFUND];
            }
            if(!is_null($order->userRefund) && $order->userRefund->status != 3){
                return ["","对不起，您添加的订单已申请退款，不允许出库。",self::ORDER_IDS_REFUND];
            }
            if(empty($boxOrder)){
                return ["","对不起，您添加的订单不在库。请检查后重新尝试。",self::ORDER_IS_NOT_DEMOSTIC];
            }
            if(empty($box)){
                return ["","包装箱不存在",self::BOX_IS_NOT_EXIST];
            }
            if($boxOrder->boxs->status == Box::BOX_STATUS_IN || $boxOrder->boxs->status == Box::BOX_STATUS_PRINTED){
                if($boxOrder->box_id != $box_id){
                    return ["","订单并不在物流箱中",self::ORDER_IS_NOT_IN_BOX];
                }
            }
            $waitoutOrder = WaitOutput::find()->where(['order_id'=>$order_id])->one();
            if(!empty($waitoutOrder)){
                return ["","请勿重复扫描",self::ORDER_IS_SCANED];
            }
            $waitout = WaitOutput::find()->where(['express_no'=>$express_no,'box_id'=>$box_id])->orderBy(['id'=>SORT_DESC])->one();
            if(!empty($waitout) && isset($waitout->orders)){
                if($waitout->orders->addr != $order->addr){
                    return ["","该订单与您上一次扫描的订单地址不一样",self::ORDER_ADDR_NO_COMPARE];
                }
            }

        }else{
            $waitout = WaitOutput::find()->where(['express_no'=>$express_no,'box_id'=>$box_id])->orderBy(['id'=>SORT_DESC])->one();
            if(!empty($waitout) && isset($waitout->orders)){
                if($waitout->orders->addr != $order->addr){
                    return ["","该订单与您上一次扫描的订单地址不一样",self::ORDER_ADDR_NO_COMPARE];
                }
            }
            if(!empty($boxOrder)){
                $boxOrder->box_id = $box_id;
                $boxOrder->update();
                //移箱后把wait_out表中记录的这个订单记录清掉,add:wuheping
                WaitOutput::deleteAll(['order_id'=>$order_id]);
            }
        }


        $model = new WaitOutput();
        $model->box_id = $box_id;
        $model->order_id = $order_id;
        $model->express_no = $express_no;
        $model->save();


        
        $ret = [];
        $ret['order']['order_id'] = $order_id;
        $ret['order']['goods_name'] = $order->stocks->name;
        $imgs = json_decode($order->stocks->imgs,true);
        $ret['order']['img'] = Yii::$app->params['staticDomain'].$imgs[0];
        $ret['order']['sku'] = $order->stockAmount->sku_value;
        $ret['order']['created_user'] = $box->managers->username;
        $ret['order']['created_time'] = date('Y-m-d H:i:s',$boxOrder->created_time);
        $ret['same_orders'] = [];
        //查找同一用户同一地址同一箱子里可以出库的订单，由以前的提示改成直接加到出库页面里 update:wuheping
        $same_address_order  = static::getSameAddressOrders($box,$order);
        if(!empty($same_address_order)){
            foreach($same_address_order as $k => $same_order){
                $ret['same_orders'][$k]['order_id'] = $same_order['order_id'];
                $ret['same_orders'][$k]['goods_name'] = $same_order['goods_name'];
                $s_imgs = json_decode($same_order['imgs'],true);
                $ret['same_orders'][$k]['img'] = Yii::$app->params['staticDomain'].$s_imgs[0];
                $ret['same_orders'][$k]['sku'] = $same_order['sku'];
                $ret['same_orders'][$k]['created_user'] = $same_order['created_user'];
                $ret['same_orders'][$k]['created_time'] = date('Y-m-d H:i:s',$same_order['created_time']);

                $model = new WaitOutput();
                $model->box_id = $box_id;
                $model->order_id = $same_order['order_id'];
                $model->express_no = $express_no;
                $model->save();
            }
        }
        return [$ret,'请求成功',Box::REQUEST_IS_SUCCESS];
    }
    /**
     * get same address orders in box
     * @param  object box
     * @param  object order
     * @return [type]           [description]
     */
    public static function getSameAddressOrders($box,$order){
        $same_address_order = [];
        foreach($box->boxOrders as $key=>$boxOrder){
            if($boxOrder->orders->id != $order->id && $boxOrder->orders->status == self::ORDER_STATUS_IS_STOCK){
                $waitout = WaitOutput::find()->where(['order_id'=>$boxOrder->orders->id])->one();
                if(empty($waitout)){
                    if($boxOrder->orders->addr == $order->addr && $boxOrder->orders->name == $order->name && (!$boxOrder->orders->userRefund || $boxOrder->orders->userRefund->status == 3)){
                        $same_address_order[$key]['order_id'] = $boxOrder->orders->id;
                        $same_address_order[$key]['goods_name'] = $boxOrder->orders->stocks->name;
                        $same_address_order[$key]['imgs'] = $boxOrder->orders->stocks->imgs;
                        $same_address_order[$key]['sku'] = $boxOrder->orders->stockAmount->sku_value;
                        $same_address_order[$key]['created_user'] = $box->managers->username;
                        $same_address_order[$key]['created_time'] = $boxOrder->created_time;
                    }
                }
                
            }
        }
        return $same_address_order;
    }
    /**
     * status desc
     * @return [type] [description]
     */
    public function statusDesc(){
        return $this->getStatusDesc($this->status,$this->pay_type);
    }
    public function getStatusDesc($status,$payType= 0) {
        switch($status) {
        case 'wait_prepay':
            if($payType == 0)
                return '订单生成，等待支付定金';
            else{
                return '订单生成，等待支付';
            }
        case 'prepayed':
            if($payType == 0){
                return '已支付定金';
            }else{
                return '已支付，等待发货';
            }
        case 'wait_pay':
            return '备货成功，等待支付余款';
        case 'payed':
        case 'packed':
            if($payType == 0){
                return '已补全款，等待发货';
            }else{
                return '已支付，等待发货';
            }
        case 'to_demostic':
            return '商品海外发货';
        case 'demostic':
            return '商品国内入库';
        case 'to_user':
            return '商品国内发出';
        case 'wait_refund':
            return '备货失败，订单关闭';
        case 'refund':
            return '定金已退回，订单关闭';
        case 'full_refund':
            return '全款已退回，订单关闭';
        case 'returned':
        case 'fail':
        case 'canceled':
            return '订单已取消';
        case 'timeout':
            return '余款支付过期，订单关闭';
        case 'post_sale':
        case 'success':
            return '已确认收货，交易成功';
        default:
            return '订单异常';
        }
    }
    /**
     * get need print order data 
     * @param  array order ids
     * @return [type]           [description]
     */
    public static function getNeedExpressData($ids){
        $orders = static::find()->where(['in','id',$ids])->asArray()->all();

        $stockIds = array();
        $skuIds = array();
        foreach($orders as $index => $order) {
            $stockIds[] = $order['stock_id'];
            $skuIds[] = $order['stock_amount_id'];
        }

        $stocks = Stock::find()->where(['in','id',$stockIds])->all();
        $skus = StockAmount::find()->where(['in','id',$skuIds])->all();

        $stocksMap = array();
        $skusMap = array();

        $black_list = require(Yii::getAlias('@app/config/black_word_list.php'));
        array_map(function($stock)use(&$stocksMap,$black_list) {
            $stocksMap[$stock->id] = $stock;
            $stocksMap[$stock->id]['name'] = @str_replace($black_list,"",$stocksMap[$stock->id]['name']);
        }, $stocks);
        array_map(function($sku)use(&$skusMap) {
            $skusMap[$sku->id] = $sku;
        }, $skus);
        $orders = array_map(function($order)use($stocksMap, $skusMap) {
            $order['stockObj'] = $stocksMap[$order['stock_id']];
            $order['skuObj'] = $skusMap[$order['stock_amount_id']];
            return $order;
        }, $orders);
        $usersMap = array();
        array_map(function($order)use(&$usersMap) {
            // $usersMap[md5($order["name"].$order["phone"].$order["cellphone"].$order["country"].$order["province"].$order["city"].$order["addr"])][] = $order;
            $usersMap[md5($order["name"].$order["addr"])][] = $order;
        }, $orders);
        ksort($usersMap);
        return $usersMap;
    }
    /**
     * get need refund amount by pay type
     * @return [type] [description]
     */
    public function getNeedRefundAmount(){
        $coupon_value = '0.00';
        if($this->coupon_id != ""){
            $coupon = Coupon::find()->where(['coupon_id'=>$this->coupon_id])->one();
            $coupon_value = $coupon->value;
        }
        
        $total_price = static::find()->select('sum_price')->where(['pay_order_id'=>$this->pay_order_id])->sum('sum_price');

        $rate = $this->sum_price / $total_price;
        if($coupon_value == '0.00'){
            if($this->pay_type == self::ALL_PAY){
                return round($this->sum_price , 2);
            }else{
                return round($this->sum_price * self::PREPAY_RATIO, 2);
            }
        }else{
            if($total_price <= $coupon_value){
                return $this->sum_price;
            }else{
                if($rate == 1){
                    return $this->sum_price;
                }else{
                     $coupon_used = sprintf("%0.2f",$rate * $coupon_value);
                     if($this->pay_type == self::ALL_PAY){
                        return sprintf("%0.2f",$this->sum_price - $coupon_used);
                    }else{
                        return sprintf("%0.2f",($this->sum_price - $coupon_used) * self::PREPAY_RATIO);
                        // return round(($this->sum_price - $coupon_value) * self::PREPAY_RATIO, 2);
                    }
                }
            }
           
        }

        
        
    }
}