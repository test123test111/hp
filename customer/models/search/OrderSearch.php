<?php

namespace customer\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Order;

/**
 * PostSearch represents the model behind the search form about `backend\models\Post`.
 */
class OrderSearch extends \customer\models\Order
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['goods_code', 'goods_quantity','goods_active','storeroom_id','recipients','recipients_address','recipients_contact','status'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Order::find()->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'owner_id'=>Yii::$app->user->id])->orderBy(['id'=>SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'goods_code' => $this->goods_code,
            'status'=>$this->status,
        ]);
        // $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
    /**
     * get refund data
     * @return [type] [description]
     */
    public static function getMyData($params){
        $query = Order::find()->with(['details'])->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'owner_id'=>Yii::$app->user->id])->orderBy(['id'=>SORT_DESC]);

        if(isset($params['order_id']) && $params['order_id'] != ""){
            $query->andWhere(['viewid'=>$params['order_id']]);
        }
        if(isset($params['begin_time']) && $params['begin_time'] != ""){
            if(isset($params['end_time']) && $params['end_time'] != ""){
                $begin_time = $params['begin_time']." 00:00:00";
                $end_time = $params['end_time']." 23:59:59";
                $query->andWhere('created >= :begin_time AND created <= :end_time',[':begin_time'=>$begin_time,":end_time"=>$end_time]);
            }
        }
        if(isset($params['status']) && $params['status'] == self::SIGN_ORDER){
            $query->andWhere(['status'=>self::SIGN_ORDER]);
        }else{
            $query->andWhere('status <> :status',[':status'=>self::SIGN_ORDER]);
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
    public static function getPreData($params){
        $query = Order::find()->with(['details'])->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'created_uid'=>Yii::$app->user->id,'status'=>self::ORDER_STATUS_IS_PRE])->orderBy(['id'=>SORT_DESC]);

        if(isset($params['order_id']) && $params['order_id'] != ""){
            $query->andWhere(['viewid'=>$params['order_id']]);
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
    public static function getDoingData($params){
        $query = Order::find()->with(['details'])
                              ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'created_uid'=>Yii::$app->user->id])
                              ->andWhere('status >= :b_status AND status <= :c_status',[':b_status'=>self::ORDER_STATUS_IS_APPROVALED,':c_status'=>self::ORDER_STATUS_IS_TRUCK])
                              ->orderBy(['id'=>SORT_DESC]);

        if(isset($params['order_id']) && $params['order_id'] != ""){
            $query->andWhere(['viewid'=>$params['order_id']]);
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
    public static function getDoneData($params){
        $query = Order::find()->with(['details'])
                              ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'created_uid'=>Yii::$app->user->id])
                              ->andWhere(['status'=>self::ORDER_STATUS_IS_SIGN])
                              ->orderBy(['id'=>SORT_DESC]);

        if(isset($params['order_id']) && $params['order_id'] != ""){
            $query->andWhere(['viewid'=>$params['order_id']]);
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
    public static function getExcepetData($params){
        $query = Order::find()->with(['details'])
                              ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'created_uid'=>Yii::$app->user->id])
                              ->andWhere('status >= :b_status AND status <= :c_status',[':b_status'=>self::ORDER_STATUS_IS_UNSIGN,':c_status'=>self::ORDER_STATUS_IS_APPROVAL_FAIL])
                              ->orderBy(['id'=>SORT_DESC]);

        if(isset($params['order_id']) && $params['order_id'] != ""){
            $query->andWhere(['viewid'=>$params['order_id']]);
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
    public static function getNeedapprovalData($params){
        $query = Order::find()->with(['details'])->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'created_uid'=>Yii::$app->user->id,'status'=>self::ORDER_STATUS_IS_NEED_APPROVAL])->orderBy(['id'=>SORT_DESC]);

        if(isset($params['order_id']) && $params['order_id'] != ""){
            $query->andWhere(['viewid'=>$params['order_id']]);
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

    public function searchByPost($orderid){
        $query = Order::find()->where(['viewid'=>$orderid,'is_del'=>Order::ORDER_IS_NOT_DEL]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }
}
