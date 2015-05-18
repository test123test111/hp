<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use backend\models\Order;
class DeliveryAbroad extends ActiveRecord
{
    const STATUS_WAIT_SETTLEMENT = 0;
    const STATUS_SETTLEMENT = 1;
    const STATUS_IGNORE = 2;
    const CHECK_PASSWORD = 'amzy9090_taoshj';

    const ORDER_STATUS_IS_WRONG = 15001;
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'delivery_abroad';
    }
    /**
     * table delivery_abroad and buyer ralationship
     * @return [type] [description]
     */
    public function getBuyers(){
        return $this->hasOne(Buyer::className(),['id'=>'buyer_id']);
    }
    /**
     * table delivery_abroad and buyer_account relationship
     * @return [type] [description]
     */
    public function getBuyerAccounts(){
        return $this->hasMany(BuyerAccount::className(),['buyer_id'=>'buyer_id']);
    }
    /**
     * delivery_abroad and order relationship
     * @return [type] [description]
     */
    public function getOrders(){
        return $this->hasOne(Order::className(),['id'=>'order_id'])->orderBy(['id'=>SORT_DESC]);
    }
    /**
     * table settlement_ignore_buyer and delivery_abroad relationship
     * @return [type] [description]
     */
    public function getIgnoreBuyer(){
        return $this->hasOne(IgnoreBuyer::className(),['buyer_id'=>'buyer_id']);
    }
    /**
     * get refund data
     * @return [type] [description]
     */
    public static function getSettlementList($params,$subpages = true){
        list($results,$pages,$count) = static::getBuyIdsNeedSettlement($params,$subpages);
        $ret = [];
        if(!empty($results)){
            foreach($results as $result){
                $data = static::formatData($params,$result);
                array_push($ret, $data);
            }
        }
        return [$ret,$pages,$count];
    }
    /**
     * format data 
     * $result is include buyer id 
     * request buyerid has order id sumprice
     * order count 
     * @param  [type] $result [description]
     * @return [type]         [description]
     */
    public static function formatData($params,$object){
        $query = static::find()->select('order_id')->where(['status'=>self::STATUS_WAIT_SETTLEMENT]);
        if(isset($params['status'])){
            $query->where(['status'=>$params['status']]);
        }
        $query->andWhere(['buyer_id'=>$object->buyer_id]);
        if(isset($params['begin_time']) && isset($params['end_time']) && $params['begin_time'] != '' && $params['end_time'] != ""){
            $begin_time = strtotime($params['begin_time']);
            $end_time = strtotime($params['end_time']);
            $query->andWhere('delivery_time >= :begin_time AND delivery_time <= :end_time',[':begin_time'=>$begin_time,"end_time"=>$end_time]);
        }
        return yii\helpers\ArrayHelper::toArray($object,[
                "common\models\DeliveryAbroad"=>[
                    'buyer_name'=>function($object){
                        if(isset($object->buyers)){
                            return $object->buyers->real_name;
                        }
                        return "";
                    },
                    'nickname'=>function($object){
                        if(isset($object->buyers)){
                            return $object->buyers->name;
                        }
                        return "";
                    },
                    'buyer_id'=>'buyer_id',
                    'orderCount'=>function($object) use ($query){
                        return $query->count();
                    },
                    'orderPrice'=>function($object) use ($query){
                        $orderIds = $query->column();
                        return Order::find()->where(['id'=>$orderIds])->sum('sum_price');
                    },
                    // 'orders'=>function($object) use ($query){
                    //     // $orderIds = $query->column();
                    //     // return Order::find()->where(['id'=>$orderIds])->all();
                    // },
                    'adjust'=>function($object) use ($query){
                        return $query->select('adjust')->sum('adjust');
                    },
                    'method'=>function($object){
                        if(isset($object->buyerAccounts) && !empty($object->buyerAccounts)){
                            return BuyerAccount::getDefaultSettlementMethod($object->buyerAccounts);
                        }
                        return "";
                    }
                ]
            ]);
    }
    /**
     * [getQuery description]
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public static function getOrderDetail($params){
        $query = static::find()->with(['orders'])->orderBy(['status'=>SORT_ASC]);
        if(isset($params['status'])){
            if($params['status'] != 'all' || $params['status'] == ''){
                $query->where(['status'=>$params['status']]);
            }
        }
        if(isset($params['buyer_id'])){
            $query->andWhere(['buyer_id'=>$params['buyer_id']]);
        }
        if(isset($params['order_id']) && $params['order_id'] != ''){
            $query->andWhere(['order_id'=>$params['order_id']]);
        }
        if(isset($params['begin_time']) && isset($params['end_time']) && $params['begin_time'] != '' && $params['end_time'] != ""){
            $begin_time = strtotime($params['begin_time']);
            $end_time = strtotime($params['end_time']);
            $query->andWhere('delivery_time >= :begin_time AND delivery_time <= :end_time',[':begin_time'=>$begin_time,"end_time"=>$end_time]);
        }
        $count = $query->count();
        $pages = new \yii\data\Pagination(['totalCount' => $count]);
        $ret = [];
        $query->offset($pages->offset)->limit(20);

        return [$query->all(),$pages,$count];

    }
    /**
     * get settlement buyer ids 
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public static function getBuyIdsNeedSettlement($params,$subpages = true){
        $query = static::find()->groupBy('buyer_id')
                               ->with(['buyers','buyerAccounts','orders'])
                               ->where(['status'=>self::STATUS_WAIT_SETTLEMENT]);
        $ret = [];
        $ignore_user = IgnoreBuyer::find()->select('buyer_id')->where(['is_ignore'=>IgnoreBuyer::BUYER_IS_IGNORE])->column();
        if(isset($params['status'])){
            $query->where(['status'=>$params['status']]);
        }
        if(isset($params['buyer_id']) && $params['buyer_id'] != ""){
            $query->andWhere(['buyer_id'=>$params['buyer_id']]);
        }
        if(isset($params['buyer_name']) && $params['buyer_name'] != ""){
            $buyer = Buyer::find()->where(['real_name'=>$params['buyer_name']])->one();
            if(empty($buyer)){
                $buyer_id = "";
            }else{
                $buyer_id = $buyer->id;
            }
            $query->andWhere(['buyer_id'=>$buyer_id]);
        }
        $query->andWhere(['not in', 'buyer_id', $ignore_user]);
        if(isset($params['begin_time']) && isset($params['end_time']) && $params['begin_time'] != '' && $params['end_time'] != ""){
            $begin_time = strtotime($params['begin_time']);
            $end_time = strtotime($params['end_time']);
            $query->andWhere('delivery_time >= :begin_time AND delivery_time <= :end_time',[':begin_time'=>$begin_time,"end_time"=>$end_time]);
        }
        if($subpages){
            $count = $query->count();
            $pages = new \yii\data\Pagination(['totalCount' => $count]);
            $query->offset($pages->offset)->limit(20);

            $results = $query->all();
            // foreach($results as $v){
            //     foreach($v as $v1){
            //         array_push($ret, $v1);
            //     }
            // }  
            return [$results,$pages,$count];
        }
        return $query->all();
        // foreach($results as $v){
        //     foreach($v as $v1){
        //         array_push($ret, $v1);
        //     }
        // }
        // return [$results,0,0];
        
    }
    /**
     * get settlement status 
     * @return [type] [description]
     */
    public function getSettlementStatus(){
        return [
            self::STATUS_WAIT_SETTLEMENT=>'待结算',
            self::STATUS_SETTLEMENT => '已结算',
            self::STATUS_IGNORE => '忽略',
        ];
    }
    /**
     * [getOrderSettlementStatus description]
     * @return [type] [description]
     */
    public function getOrderSettlementStatus(){
        return $this->getSettlementStatus()[$this->status];
    }
    /**
     * ignore order 
     * @return [type] [description]
     */
    public function ignoreOrder(){
        if($this->status == self::STATUS_IGNORE){
            $this->status = self::STATUS_WAIT_SETTLEMENT;
        }elseif($this->status == self::STATUS_WAIT_SETTLEMENT){
            $this->status = self::STATUS_IGNORE;
        }
        if($this->update()){
            return true;
        }
        return false;
    }
    /**
     * re ignore order 
     * @return [type] [description]
     */
    public function reIgnoreOrder(){
        $this->status = self::STATUS_WAIT_SETTLEMENT;
        if($this->update()){
            return true;
        }
        return false;
    }
    /**
     * [adjustPrice description]
     * @return [type] [description]
     */
    public function adjustPrice($price){
        $this->adjust = $price;
        if($this->update()){
            return true;
        }
        return false;
    }
    /**
     * [validSettlementPw description]
     * @return [type] [description]
     */
    public static function validSettlementPw($password){
        if(md5($password) == md5(self::CHECK_PASSWORD)){
            return true;
        }
        return false;
    }
    /**
     * get need settlement data by order ids
     * group by buyer_id 
     * @param  [type] $order_ids [description]
     * @return [type]            [description]
     */
    public static function getSettlementDataByOrderIds($order_ids){
        $ret = static::getDataGroupBuyerid($order_ids);
        $sum_price = Order::find()->where(['id'=>$order_ids])->sum('sum_price');
        $adjust = static::find()->where(['order_id'=>$order_ids])->sum('adjust');
        return [$ret,$sum_price,$adjust];
    }

    /**
     * checkout order ids 
     * build table settlement settlement_list
     * @param  [type] $order_ids [description]
     * @return [type]            [description]
     */
    public static function checkout($order_ids,$begin_time,$end_time){
        $results = static::getDataGroupBuyerid($order_ids);
        $sum_price = Order::find()->where(['id'=>$order_ids])->sum('sum_price');
        $adjust = static::find()->where(['order_id'=>$order_ids])->sum('adjust');
        //create table settlement and settlement list
        $db = self::getDb();
        $transaction = $db->beginTransaction();
        try {
            $model = new Settlement;
            $model->sum_price = $sum_price;
            $model->adjust = $adjust;
            $model->order_count = count($order_ids);
            $model->buyer_count = count($results);
            $model->delivery_begin_time = strtotime($begin_time);
            $model->delivery_end_time = strtotime($end_time);
            $model->created_uid = Yii::$app->user->id;
            $model->created_time = time();
            $model->save();

            $insert_array = [];
            foreach($results as $result){
                foreach($result['orders'] as $order){
                    $array = [$model->id,$order->order_id,$order->buyer_id];
                    array_push($insert_array, $array);
                }
                
            }
            $db->createCommand()->batchInsert('settlement_list', ['settlement_id', 'order_id','buyer_id'],$insert_array)->execute();
            // $unpaid = [];
            // $faildpaid = [];
            // foreach($results as $result){
            //     if($result->method == BuyerAccount::TYPE_IS_ALIPAY){
            //         //pay to buyer
            //         //update settlement list settlement_uid and settlement_time
            //     }else{
            //         array_push($unpaid, $result);
            //     }
            // }
            // if(empty($faildpaid)){
            //     if(empty($unpaid)){
            //         $model->status = Settlement::STATUS_IS_PAY;
            //     }else{
            //         $model->status = Settlement::STATUS_IS_PARTPAY;
            //     }
            // }
            // $model->status = Settlement::STATUS_IS_PAY;
            // $model->update();
            // static::updateAll(['status'=>self::STATUS_SETTLEMENT],['order_id'=>$order_ids]);
            $transaction->commit();
            return $model;

        }catch (\Exception $e) {
            $transaction->rollBack();
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        
    }
    /**
     * [getDataGroupBuyerid description]
     * @param  [type] $order_ids [description]
     * @return [type]            [description]
     */
    public static function getDataGroupBuyerid($order_ids){
        if(!is_array($order_ids)){
            $order_ids = [$order_ids];
        }
        $results = static::find()->with(['orders','buyerAccounts','buyers'])->where(['order_id'=>$order_ids])->andWhere(['status'=>self::STATUS_WAIT_SETTLEMENT])->all();
        $ret = [];
        if(!empty($results)){
            foreach($results as $key=>$result){
                if(!isset($ret[$result->buyer_id]['sumprice'])){
                    $ret[$result->buyer_id]['sumprice'] = $result->orders->sum_price;
                }else{
                    $ret[$result->buyer_id]['sumprice'] += $result->orders->sum_price;
                }
                if(!isset($ret[$result->buyer_id]['adjust'])){
                    $ret[$result->buyer_id]['adjust'] = $result->adjust;
                }else{
                    $ret[$result->buyer_id]['adjust'] += $result->adjust;
                }
                $ret[$result->buyer_id]['orders'][$key] = $result;
                $ret[$result->buyer_id]['buyer_id'] = $result->buyer_id;
                $ret[$result->buyer_id]['buyer_realname'] = $result->buyers->real_name;
                $ret[$result->buyer_id]['buyer_nickname'] = $result->buyers->name;
                $ret[$result->buyer_id]['name'] = $result->buyers->name;
                $ret[$result->buyer_id]['method'] = BuyerAccount::getDefaultSettlementMethod($result->buyerAccounts);
                $ret[$result->buyer_id]['banknumber'] = "";
                foreach($result->buyerAccounts as $account){
                    if($account->type == BuyerAccount::TYPE_IS_ALIPAY){
                        $ret[$result->buyer_id]['banknumber'] = $account->no;
                    }
                }
                
            }
        }
        return $ret;
    }
    /**
     * [getQuery description]
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public static function getAllNeedPayOrderIds($params){
        $query = static::find()->select('order_id')->where(['status'=>self::STATUS_WAIT_SETTLEMENT])->orderBy(['delivery_time'=>SORT_ASC]);
        if(isset($params['buyer_id'])){
            $query->andWhere(['buyer_id'=>$params['buyer_id']]);
        }
        if(isset($params['begin_time']) && isset($params['end_time']) && $params['begin_time'] != '' && $params['end_time'] != ""){
            $begin_time = strtotime($params['begin_time']);
            $end_time = strtotime($params['end_time']);
            $query->andWhere('delivery_time >= :begin_time AND delivery_time <= :end_time',[':begin_time'=>$begin_time,"end_time"=>$end_time]);
        }
        
        return $query->column();   
    }
    /**
     * get refund data
     * @return [type] [description]
     */
    public static function getBuyersList($params,$subpages = true){
        list($results,$page,$count) = static::getBuyIdsNeedSettlement($params,$subpages);
        $ret = [];
        if(!empty($results)){
            foreach($results as $result){
                $data = static::getOrderIds($params,$result);
                $results = static::getDataGroupBuyerid($data);
                foreach($results as $value){
                    array_push($ret, $value);
                }
            }
        }
        return $ret;
    }
    /**
     * format data 
     * $result is include buyer id 
     * request buyerid has order id sumprice
     * order count 
     * @param  [type] $result [description]
     * @return [type]         [description]
     */
    public static function getOrderIds($params,$object){
        $query = static::find()->select('order_id')->where(['status'=>self::STATUS_WAIT_SETTLEMENT]);
        if(isset($params['status'])){
            $query->where(['status'=>$params['status']]);
        }
        if(isset($params['begin_time']) && isset($params['end_time']) && $params['begin_time'] != '' && $params['end_time'] != ""){
            $begin_time = strtotime($params['begin_time']);
            $end_time = strtotime($params['end_time']);
            $query->andWhere('delivery_time >= :begin_time AND delivery_time <= :end_time',[':begin_time'=>$begin_time,"end_time"=>$end_time]);
        }
        $query->andWhere(['buyer_id'=>$object->buyer_id]);
        return $query->column();
    }
    /**
     * ignore batch orders
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public static function ignoreBatchOrders($params){
        $query = static::find()->select('order_id');
        if(isset($params['status'])){
            if($params['status'] != 'all'){
                $query->where(['status'=>$params['status']]);
            }
        }
        if(isset($params['buyer_id'])){
            $query->andWhere(['buyer_id'=>$params['buyer_id']]);
        }
        if(isset($params['order_id']) && $params['order_id'] != ''){
            $query->andWhere(['order_id'=>$params['order_id']]);
        }
        if(isset($params['begin_time']) && isset($params['end_time']) && $params['begin_time'] != '' && $params['end_time'] != ""){
            $begin_time = strtotime($params['begin_time']);
            $end_time = strtotime($params['end_time']);
            $query->andWhere('delivery_time >= :begin_time AND delivery_time <= :end_time',[':begin_time'=>$begin_time,"end_time"=>$end_time]);
        }
        $order_ids = $query->column();
        return static::updateAll(['status'=>self::STATUS_IGNORE],['and', 'status<>'.self::STATUS_SETTLEMENT, ['in', 'order_id', $order_ids]]);
    }
    /**
     * get detail url
     * @param  [type] $new_url [description]
     * @param  [type] $type    [description]
     * @param  [type] $value   [description]
     * @return [type]          [description]
     */
    public function getUrl($new_url,$type, $value)
    {
        $params = \Yii::$app->request->queryParams;
        // if ((isset($params[$type]) && $params[$type] != $value) || !isset($params[$type])) {
            unset($params['page']);
            unset($params[$type]);
            $params[$type] = $value;
            foreach ($params as $key => $value) {
                $new_url .= $key . "=" . $value . "&";
            }
            return rtrim($new_url, "&");
        }

    // }
}
