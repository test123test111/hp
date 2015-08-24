<?php

namespace hhg\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Order;
use backend\models\Owner;
use backend\models\Material;

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
        if(isset($params['type']) && $params['type'] !=""){
            $query->andWhere(['type'=>$params['type']]);
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
        if(isset($params['type']) && $params['type'] !=""){
            $query->andWhere(['type'=>$params['type']]);
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
        if(isset($params['type']) && $params['type'] !=""){
            $query->andWhere(['type'=>$params['type']]);
        }
        $count = $query->count();
        $str = "序号,申请人,申请订单日期,订单号,是否借用,库房位置,起运城市,收货城市,收货地址,收货人,运输方式,发货日期,订单状态,所属人,部门,组别,一级产品线,二级产品线,备注信息\n";
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
                if($result->is_borrow == 0 ){
                    $data[$i]['is_borrow'] = '否';
                }else{
                    $data[$i]['is_borrow'] = '是';
                }
                $data[$i]['createuser'] = $result->createuser->english_name;
                $data[$i]['created'] = date('Y-m-d H:i',strtotime($result->created));
                $data[$i]['viewid'] = $result->viewid;
                $data[$i]['storeroom'] = $result->storeroom->name;
                $data[$i]['send_city'] = $result->storeroom->citydata->name;
                $data[$i]['to_city'] = $result->to_city;
                $data[$i]['address'] = $result->to_province.$result->to_city.$result->to_district.$result->contact;
                $data[$i]['user'] = $result->recipients;
                $data[$i]['transporttype'] = $result->getMyTransportType();
                if($result->st_send_date != 0){
                    $data[$i]['send_date'] = date('Y-m-d H:i',$result->st_send_date);
                }else{
                    $data[$i]['send_date'] = "";
                }
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
                $str .= $data[$i]['id'].",".$data[$i]['createuser'].",".$data[$i]['created'].",".$data[$i]['viewid'].",".$data[$i]['is_borrow'].','.$data[$i]['storeroom'].",".$data[$i]['send_city'].",".$data[$i]['to_city'].",".$data[$i]['address'].",".$data[$i]['user'].",".$data[$i]['transporttype'].",".$data[$i]['send_date'].",".$data[$i]['status'].",".$data[$i]['owner'].",".$data[$i]['department'].",".$data[$i]['category'].",".$data[$i]['productline'].",".$data[$i]['producttwoline'].",".$data[$i]['info']."\r\n"; //用引文逗号分开
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
        if(isset($params['type']) && $params['type'] !=""){
            $query->andWhere(['type'=>$params['type']]);
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
    /**
     * [getDisplayConsumeData description]
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public static function getDisplayConsumeData($params)
    {
        $query = Order::find()->with(['details','consume','storeroom'=>function($query){
                                    return $query->with('citydata');
                                },'createduser','package'])
                              ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL])
                              //->andWhere('status >= :b_status AND status <= :c_status',[':b_status'=>self::ORDER_STATUS_IS_PACKAGE,":c_status"=>self::ORDER_STATUS_IS_UNSIGN])
                              ->andWhere(['status'=>Order::ORDER_STATUS_IS_SIGN])
                              ->orderBy(['id'=>SORT_DESC]);

        if(isset($params['created_uid']) && $params['created_uid'] != ""){
            $owner = Owner::find()->where(['english_name'=>$params['created_uid']])->one();
            if(!empty($owner)){
                $query->andWhere(['created_uid'=>$owner->id]);
            }else{
                $query->andWhere(['created_uid'=> -1]);
            }
            
        }
        if(isset($params['begin_time']) && $params['begin_time'] != ""){
            if(isset($params['end_time']) && $params['end_time'] != ""){
                $begin_time = $params['begin_time']." 00:00:00";
                $end_time = $params['end_time']." 23:59:59";
                $query->andWhere('created >= :begin_time AND created <= :end_time',[':begin_time'=>$begin_time,":end_time"=>$end_time]);
            }
        }
        if(isset($params['category']) && $params['category'] !=""){
            $query->andWhere(['category_id'=>$params['category']]);
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
    public static function getConsumeData($params){
        $query = Order::find()->with(['details','consume','storeroom'=>function($query){
                                    return $query->with('citydata');
                                },'createduser','package'])
                              ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL])
                              //->andWhere('status >= :b_status AND status <= :c_status',[':b_status'=>self::ORDER_STATUS_IS_PACKAGE,":c_status"=>self::ORDER_STATUS_IS_UNSIGN])
                              ->andWhere(['status'=>Order::ORDER_STATUS_IS_SIGN])
                              ->orderBy(['id'=>SORT_DESC]);

        if(isset($params['created_uid']) && $params['created_uid'] != ""){
            $owner = Owner::find()->where(['english_name'=>$params['created_uid']])->one();
            if(!empty($owner)){
                $query->andWhere(['created_uid'=>$owner->id]);
            }else{
                $query->andWhere(['created_uid'=> -1]);
            }
            
        }
        if(isset($params['begin_time']) && $params['begin_time'] != ""){
            if(isset($params['end_time']) && $params['end_time'] != ""){
                $begin_time = $params['begin_time']." 00:00:00";
                $end_time = $params['end_time']." 23:59:59";
                $query->andWhere('created >= :begin_time AND created <= :end_time',[':begin_time'=>$begin_time,":end_time"=>$end_time]);
            }
        }
        if(isset($params['category']) && $params['category'] !=""){
            $query->andWhere(['category_id'=>$params['category']]);
        }
        $count = $query->count();
        $str = "序号,订单类型,库房位置,申请人,申请订单日期,预算所有人,起运城市,收货城市,运输时效,运输类别,重量(KG),包装数量,费率,分拣费,运费,保险费,包装材料费,其他费用,总计,预估价格,发货日期,所属人,部门,组别,一级产品线,二级产品线,收货地址,收货人,订单号,备注\n";
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
                $data[$i]['type'] = '日常订单';
                $data[$i]['storeroom'] = $result->storeroom->name;
                $data[$i]['created_uid'] = $result->createduser->english_name;
                $data[$i]['created'] = date('Y-m-d H:i',strtotime($result->created));
                $data[$i]['budget_owner'] = $result->createduser->english_name;
                $data[$i]['send_city'] = isset($result->storeroom->citydata) ? $result->storeroom->citydata->name : "";
                $data[$i]['to_city'] = $result->to_city;
                $data[$i]['transporttype'] = $result->getMyTransportType();
                if($result->to_type == self::TO_TYPE_USER){
                    $data[$i]['transport_cat'] = '寄送收件人';
                }else{
                    $data[$i]['transport_cat'] = '寄送平台库';
                }
               
                $weight = 0;
                $owner_str = '';
                $department_str = '';
                $category_str = '';
                $productline_str = '';
                $producttwoline_str = '';
                foreach($result->details as $detail){
                    $material = Material::findOne($detail->material_id);
                    $materialWeight = $detail->quantity * $material->weight;
                    $weight += $materialWeight;
                    $owner_str .= $detail->owner->english_name.'、';
                    $department_str .= $detail->owner->departments->name.'、';
                    $category_str .= $detail->owner->categorys->name.'、';
                    $productline_str .= $detail->owner->productlines->name.'、';
                    $producttwoline_str .= $detail->owner->producttwolines->name.'、';
                }
                $weight = ceil($weight * 1.05);
                $data[$i]['weight'] = $result->package->throw_weight;
                $data[$i]['package_num'] = isset($result->package) ? $result->package->num : 0;
                $data[$i]['tariff'] = $result->tariff;
                $data[$i]['fenjian'] = $result->fenjian_fee;
                $data[$i]['ship_fee'] = $result->real_ship_fee;
                $data[$i]['insurance_price'] = $result->insurance_price;
                $data[$i]['package_fee'] = isset($result->package) ? $result->package->package_fee : 0;
                $data[$i]['other_fee'] = isset($result->package) ? $result->package->other_fee : 0;
                $data[$i]['total_fee'] = $result->fenjian_fee + $result->real_ship_fee + $result->insurance_price + $data[$i]['package_fee'] + $data[$i]['other_fee'];
                $data[$i]['budget_fee'] = $result->fenjian_fee + $result->ship_fee;
                if($result->st_send_date != 0){
                    $data[$i]['send_date'] = date('Y-m-d H:i',$result->st_send_date);
                }else{
                    $data[$i]['send_date'] = "";
                }

                $data[$i]['owner'] = $owner_str;
                $data[$i]['department'] = $department_str;
                $data[$i]['category'] = $category_str;
                $data[$i]['productline'] = $productline_str;
                $data[$i]['producttwoline'] = $producttwoline_str;
                $data[$i]['to_address'] = $result->to_province.$result->to_city.$result->to_district.$result->contact;
                $data[$i]['recipients'] = $result->recipients;
                $data[$i]['viewid'] = $result->viewid;
                $data[$i]['info'] = $result->info;
                $str .= $data[$i]['id'].",".$data[$i]['type'].",".$data[$i]['storeroom'].",".$data[$i]['created_uid'].",".$data[$i]['created'].",".
                                    $data[$i]['budget_owner'].","
                                    .$data[$i]['send_city'].","
                                    .$data[$i]['to_city'].","
                                    .$data[$i]['transporttype'].","
                                    .$data[$i]['transport_cat'].","
                                    .$data[$i]['weight'].","
                                    .$data[$i]['package_num'].","
                                    .$data[$i]['tariff'].","
                                    .$data[$i]['fenjian'].","
                                    .$data[$i]['ship_fee'].","
                                    .$data[$i]['insurance_price'].","
                                    .$data[$i]['package_fee'].","
                                    .$data[$i]['other_fee'].","
                                    .$data[$i]['total_fee'].","
                                    .$data[$i]['budget_fee'].","
                                    .$data[$i]['send_date'].","
                                    .rtrim($data[$i]['owner'],'、').","
                                    .rtrim($data[$i]['department'],'、').",".rtrim($data[$i]['category'],'、').",".rtrim($data[$i]['productline'],'、').",".rtrim($data[$i]['producttwoline'],'、').","
                                    .$data[$i]['to_address'].","
                                    .$data[$i]['recipients'].","
                                    .$data[$i]['viewid'].","
                                    .$data[$i]['info']."\r\n"; //用引文逗号分开
                $i++;
            }
           
            $offset += $limit;
            if ($offset > $count) {
                break;
            }
        }
        return $str;
    }
}
