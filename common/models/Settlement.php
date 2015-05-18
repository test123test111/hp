<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use backend\models\Manager;
class Settlement extends ActiveRecord
{
    const STATUS_IS_PAY = 1;
    const STATUS_IS_UNPAY = 2;
    const STATUS_IS_PARTPAY = 3;
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'settlement';
    }
    /**
     * get manager object status
     */
    public function getSettlementStatus(){
        return function ($model) {
            return $this->getCanUseStatus()[$model->status];
        };
    }
    /**
     * table manager and table settlement relationship
     * @return [type] [description]
     */
    public function getManager(){
        return $this->hasOne(Manager::className(),['id'=>'created_uid']);
    }
    /**
     * user status list array
     * @return [type] [description]
     */
    public function getCanUseStatus(){
        return [
            self::STATUS_IS_PAY=>'已结算',
            self::STATUS_IS_UNPAY=>'未结算',
            self::STATUS_IS_PARTPAY=>'部分结算',
        ];
    }
    /**
     * table settlement and settlement_detail relationship
     * @return [type] [description]
     */
    public function getDetails(){
        return $this->hasMany(SettlementList::className(),['settlement_id'=>'id']);
    }
    /**
     * 
     * @return [type] [description]
     */
    public function attributeLabels(){
        return [
            'id'=>'结算批号',
            'status'=>'结算状态',
            'sum_price'=>'应结金额',
            'order_count'=>'结算订单',
            'created_time'=>'结算时间',
            'created_uid'=>'结算人',
        ];
    }
    /**
     * get detail 
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public static function getDetailById($id){
        $query = SettlementList::find()->select('order_id')->where(['settlement_id'=>$id]);
        $order_ids = $query->column();
        return static::getDataGroupBuyerid($order_ids);
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
        $results = DeliveryAbroad::find()->with([
                                'orders'=>function($query){
                                    return $query->with(['stocks','stockAmount','delivery','pack']);
                                },
                                'buyerAccounts',
                                'buyers'])
                                ->where(['order_id'=>$order_ids])->all();
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
                $ret[$result->buyer_id]['status'] = $result->status;
                $ret[$result->buyer_id]['orders'][$key] = $result;
                $ret[$result->buyer_id]['buyer_id'] = $result->buyer_id;
                $ret[$result->buyer_id]['buyer_realname'] = $result->buyers->real_name;
                $ret[$result->buyer_id]['buyer_nickname'] = $result->buyers->name;
                $ret[$result->buyer_id]['name'] = $result->buyers->name;
                $ret[$result->buyer_id]['method'] = BuyerAccount::getDefaultSettlementMethod($result->buyerAccounts);
                $ret[$result->buyer_id]['receive_name'] = "";
                $ret[$result->buyer_id]['banknumber'] = "";
                foreach($result->buyerAccounts as $account){
                    if($account->type == BuyerAccount::TYPE_IS_ALIPAY){
                        $ret[$result->buyer_id]['banknumber'] = $account->no;
                        $ret[$result->buyer_id]['receive_name'] = $account->name;
                    }
                }
                
            }
        }
        return $ret;
    }
    /**
     * do settlement 
     * @param  [type] $buyer_id [description]
     * @return [type]           [description]
     */
    public function doSettlement($buyer_id){
        $db = self::getDb();
        $transaction = $db->beginTransaction();
        try {
            SettlementList::updateAll(['status'=>SettlementList::STATUS_IS_SETTLEMENT],['buyer_id'=>$buyer_id,'settlement_id'=>$this->id]);
            $this->settlement_count = $this->settlement_count + 1;
            // $this->update();
            if($this->settlement_count == $this->buyer_count){
                $this->status = self::STATUS_IS_PAY;
                $this->update();
            }
            $order_ids = SettlementList::find()->select('order_id')->where(['buyer_id'=>$buyer_id,'settlement_id'=>$this->id])->column();
            DeliveryAbroad::updateAll(['status'=>DeliveryAbroad::STATUS_SETTLEMENT],['order_id'=>$order_ids]);
            $transaction->commit();
            return true;

        }catch (\Exception $e) {
            $transaction->rollBack();
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}
