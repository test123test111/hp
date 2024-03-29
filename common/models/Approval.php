<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use backend\models\Order;
use backend\models\OrderDetail;
class Approval extends ActiveRecord
{
    const TYPE_IS_MATERIAL = 0;
    const TYPE_IS_FEE = 1;
    const TYPE_IS_BUDGET = 2;
    
    const STATUS_IS_UNHANDLE = 0;
    const STATUS_IS_PASS = 1;
    const STATUS_IS_REJECT = 2;

    const SEND_EMAIL = 1;
    const NOT_SEND_EMAIL = 0;
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'approval';
    }
    /**
     * table approval and order detail relationship
     * @return [type] [description]
     */
    public function getOrders(){
        return $this->hasMany(Order::className(),['id'=>'order_id']);
    }
    /**
     * get refund data
     * @return [type] [description]
     */
    public static function getMyData($params){
        $query = Static::find()->with(['orders'])->where(['owner_id'=>Yii::$app->user->id,'status'=>self::STATUS_IS_UNHANDLE])->orderBy(['id'=>SORT_DESC]);

        if(isset($params['order_id']) && $params['order_id'] != ""){
            $order = Order::find()->where(['viewid'=>$params['order_id']])->one;
            $query->andWhere(['order_id'=>$order->viewid]);
        }
        if(isset($params['type']) && $params['type'] != ""){
            $query->andWhere(['type'=>$params['type']]);
        }
        if(isset($params['begin_time']) && $params['begin_time'] != ""){
            if(isset($params['end_time']) && $params['end_time'] != ""){
                $begin_time = $params['begin_time']." 00:00:00";
                $end_time = $params['end_time']." 23:59:59";
                $query->andWhere('created >= :begin_time AND created <= :end_time',[':begin_time'=>$begin_time,":end_time"=>$end_time]);
            }
        }
        $count = $query->count();
        $pages = new \yii\data\Pagination(['totalCount' => $count]);
        $ret = [];
        $query->offset($pages->offset)->limit(20);

        $data = $query->all();
        return [$data,$pages,$count];
    }
    /**
     * get refund data
     * @return [type] [description]
     */
    public static function getHhgData($params){
        $query = Static::find()->with(['orders'])->where(['status'=>self::STATUS_IS_UNHANDLE,'type'=>self::TYPE_IS_MATERIAL])->orderBy(['id'=>SORT_DESC]);

        if(isset($params['order_id']) && $params['order_id'] != ""){
            $order = Order::find()->where(['viewid'=>$params['order_id']])->one;
            $query->andWhere(['order_id'=>$order->viewid]);
        }
        if(isset($params['type']) && $params['type'] != ""){
            $query->andWhere(['type'=>$params['type']]);
        }
        if(isset($params['begin_time']) && $params['begin_time'] != ""){
            if(isset($params['end_time']) && $params['end_time'] != ""){
                $begin_time = $params['begin_time']." 00:00:00";
                $end_time = $params['end_time']." 23:59:59";
                $query->andWhere('created >= :begin_time AND created <= :end_time',[':begin_time'=>$begin_time,":end_time"=>$end_time]);
            }
        }
        $count = $query->count();
        $pages = new \yii\data\Pagination(['totalCount' => $count]);
        $ret = [];
        $query->offset($pages->offset)->limit(20);

        $data = $query->all();
        return [$data,$pages,$count];
    }
    /**
     * get new approval 
     * @param  string $owner_id [description]
     * @return [type]           [description]
     */
    public static function getNewArroval($owner_id = ""){
        $query = static::find()->where(['status'=>self::STATUS_IS_UNHANDLE,'type'=>self::TYPE_IS_MATERIAL]);
        if($owner_id){
            $query->andWhere(['owner_id'=>$owner_id]);
        }
        return $query->count();
    }
    /**
     * [getNeedSendEmail description]
     * @return [type] [description]
     */
    public static function getNeedSendEmail(){
        return static::find()->where(['status'=>self::STATUS_IS_UNHANDLE,'send_email'=>self::NOT_SEND_EMAIL])->one();
    }
}
