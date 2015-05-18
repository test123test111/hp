<?php
namespace backend\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
class Pack extends ActiveRecord {
    const PACK_IS_NOT_EXIST = 13001;
    const ORDER_IS_INPUT = 13002;
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'pack';
    }
    /**
     * input pack include orders 
     * @param  array order_ids
     * @return [type] [description]
     */
    public static function inputOrders($order_ids,$box_id = ""){
        $valid = static::checkOrderStatus($order_ids);
        if(!$valid){
            return ["","您选择的订单中已经有已经出库或已经入库的订单,请勿重复入库",self::ORDER_IS_INPUT];
        }
        //create a new box
        if(empty($box_id)){
            $result = static::createBoxAndInputOrder($order_ids);
        }else{
            $result = static::inputOrderInbox($order_ids,$box_id);
        }
        if(!$result){
            return ["","您选择的订单已经在其他物流箱中,请勿重复入箱",Box::SYSTEM_ERROR];
        }
        return ["","批量入库成功",Box::REQUEST_IS_SUCCESS];
    }
    /**
     * create a new box and put in orders
     * @param  [type] $order_ids [description]
     * @return [type]            [description]
     */
    public static function createBoxAndInputOrder($order_ids){
        $model = new Box;
        $model->status = Box::BOX_STATUS_CREATED;
        if($model->save()){
            $key = Yii::$app->params['box_print_redis_key'];
            $print_type = Printer::TYPE_IS_BOX;
            $printer_id = Printer::getPrinterIdByTypeAndUser($print_type,Yii::$app->user->id);
            $key .= $printer_id;
            Yii::$app->redis->executeCommand('LPUSH',[$key,$model->id]);

            return static::createOrderBox($order_ids,$model->id);
        }
        return false;
    }
    /**
     * input order into exist box
     * @param  [type] $order_ids [description]
     * @param  [type] $box_id    [description]
     * @return [type]            [description]
     */
    public static function inputOrderInbox($order_ids,$box_id){
        $box = Box::findOne($box_id);
        if(empty($box) || $box->status != Box::BOX_STATUS_CREATED){
            return false;
        }
        return static::createOrderBox($order_ids,$box_id);
    }
    /**
     * check order is input in
     * if input return false
     * @param  array order_ids
     * @return trur|false
     */
    public static function checkOrderStatus($order_ids){
        $flag = true;
        foreach($order_ids as $order_id){
            $order = Order::findOne($order_id);
            if($order->status == Order::ORDER_STATUS_IS_STOCK){
                $flag = false;
            }elseif($order->status == Order::ORDER_STATUS_IS_SEND){
                $flag = false;
            }elseif($order->status == Order::ORDER_STATUS_IS_REFUND){
                $flag = false;
            }elseif($order->status == Order::ORDER_STATUS_IS_FULLREFUND){
                $flag = false;
            }else{
                continue;
            }
        }
        return $flag;
    }
    /**
     * [createOrderBox description]
     * @param  [type] $order_ids [description]
     * @param  [type] $box_id    [description]
     * @return [type]            [description]
     */
    public static function createOrderBox($order_ids,$box_id){
        $boxOrders = BoxOrder::find()->where(['order_id'=>$order_ids])->all();
        if(empty($boxOrders)){
            foreach($order_ids as $order_id){
                $boxOrder = new BoxOrder;
                $boxOrder->box_id = $box_id;
                $boxOrder->order_id = $order_id;
                $boxOrder->save();
            }
            return true;
        }
        return false;
    }
    /**
     * get orders by pack
     * object pack
     * @return [type] [description]
     */
    public function getOrders(){
        $data = [];
        $data['pack_id'] = $this->id;
        $data['logistic_provider'] = $this->logistic_provider;
        $data['logistic_no'] = $this->logistic_no;
        $data['orders'] = [];
        $orders = Order::find()->where(['pack_id'=>$this->id])->all();
        $data['orders_count'] = count($orders);
        foreach($orders as $key=>$order){
            $data['orders'][$key]['order_id'] = $order->id;
            $data['orders'][$key]['stock_id'] = $order->stock_id;
            if(isset($order->stocks) && !empty($order->stocks)){
                $data['orders'][$key]['goods_name'] = $order->stocks->name;
                $imgs = json_decode($order->stocks->imgs,true);
                $data['orders'][$key]['img'] = $imgs[0];
            }else{
                $data['orders'][$key]['goods_name'] = ""; 
                $data['orders'][$key]['img'] = "";
            }
            if(!empty($order->stockAmount)){
                $data['orders'][$key]['sku'] = $order->stockAmount->sku_value;
            }else{
                $data['orders'][$key]['sku'] = "";
            }
            $data['orders'][$key]['status'] = $order->status;
            $data['orders'][$key]['created_time'] = "";
            $data['orders'][$key]['created_user'] = "";
            if(isset($order->boxOrders) && !empty($order->boxOrders)){
                $data['orders'][$key]['created_time'] = date('Y-m-d H:i:s',$order->boxOrders->created_time);
                $data['orders'][$key]['created_user'] = $order->boxOrders->managers->username;
            }
        }
        return $data;
    }
}