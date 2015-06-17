<?php

namespace hhg\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Order;

/**
 * PostSearch represents the model behind the search form about `backend\models\Post`.
 */
class OrderSearch extends Order
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
        $query = Order::find()->with(['details'=>function($query){
                                    return $query->with('material');
                                }])->where(['is_del'=>Order::ORDER_IS_NOT_DEL])->orderBy(['id'=>SORT_DESC]);

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
        $query = Order::find()->with(['details'=>function($query){
                                    return $query->with('material');
                                }])->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL,'status'=>self::ORDER_STATUS_IS_PRE])->orderBy(['id'=>SORT_DESC]);

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
                              ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL,])
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
                              ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL])
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
     * export done order data 
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public static function getExportDoneData($params){
        $query = Order::find()->with([
                              'details'=>function($query){
                                  return $query->with(['owner'=>function($query){
                                        return $query->with(['departments','categorys','productlines','producttwolines']);
                                  }]);
                              },
                              'createuser',
                              'storeroom'=>function($query){
                                return $query->with('citydata');
                              }])
                              ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL])
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
        $str = "序号,申请人,申请订单日期,订单号,库房位置,起运城市,收货城市,收货地址,收货人,运输方式,发货日期,订单状态,所属人,部门,组别,一级产品线,二级产品线,备注信息\n";
        $offset = 0;
        $limit = 100;
        $data = [];
        $i = 1;
        while(true){
            $results = $query->limit($limit)->offset($offset)->all();
            if(empty($results)){
                break;
            }
            foreach($results as $key =>$result){
                $data[$i]['id'] = $i;
                $data[$i]['createuser'] = $result->createuser->english_name;
                $data[$i]['created'] = date('Y-m-d H:i',strtotime($result->created));
                $data[$i]['viewid'] = $result->viewid;
                $data[$i]['storeroom'] = $result->storeroom->name;
                $data[$i]['send_city'] = $result->storeroom->citydata->name;
                $data[$i]['to_city'] = $result->to_city;
                $data[$i]['address'] = $result->to_province.$result->to_city.$result->to_district.$result->contact;
                $data[$i]['user'] = $result->recipients;
                $data[$i]['transporttype'] = $result->getMyTransportType();
                $data[$i]['send_date'] = "";
                $data[$i]['status'] = '已完成';
                $data[$i]['owner'] = $result->details[0]->owner->english_name;
                if(isset($result->details[0]->owner->departments)){
                    $data[$i]['department'] = $result->details[0]->owner->departments->name;
                }else{
                    $data[$i]['department'] = "";
                }
                if(isset($result->details[0]->owner->categorys)){
                    $data[$i]['category'] = $result->details[0]->owner->categorys->name;
                }else{
                    $data[$i]['category'] = "";
                }
                if(isset($result->details[0]->owner->productlines)){
                    $data[$i]['productline'] = $result->details[0]->owner->productlines->name;
                }else{
                    $data[$i]['productline'] = "";
                }
                if(isset($result->details[0]->owner->producttwolines)){
                    $data[$i]['producttwoline'] = $result->details[0]->owner->producttwolines->name;
                }else{
                    $data[$i]['producttwoline'] = "";
                }
                $data[$i]['info'] = $result->info;
                $str .= $data[$i]['id'].",".$data[$i]['createuser'].",".$data[$i]['created'].",".$data[$i]['viewid'].",".$data[$i]['storeroom'].",".$data[$i]['send_city'].",".$data[$i]['to_city'].",".$data[$i]['address'].",".$data[$i]['user'].",".$data[$i]['transporttype'].",".$data[$i]['send_date'].",".$data[$i]['status'].",".$data[$i]['owner'].",".$data[$i]['department'].",".$data[$i]['category'].",".$data[$i]['productline'].",".$data[$i]['producttwoline'].",".$data[$i]['info']."\r\n"; //用引文逗号分开
                $i++;
            }
           
            $offset += $limit;
            if ($offset > $count) {
                break;
            }
        }
        return $str;
    }
    /**
     * get refund data
     * @return [type] [description]
     */
    public static function getExcepetData($params){
        $query = Order::find()->with(['details'])
                              ->where(['is_del'=>Order::ORDER_IS_NOT_DEL])
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
        $query = Order::find()->with(['details'])->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL,'status'=>self::ORDER_STATUS_IS_NEED_APPROVAL])->orderBy(['id'=>SORT_DESC]);

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
