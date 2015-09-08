<?php

namespace customer\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Order;
use backend\models\Owner;
use backend\models\Storeroom;
use common\models\Category;
use common\models\Department;
use customer\models\Material;
use customer\models\OrderDetail;
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
                                }])->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'owner_id'=>Yii::$app->user->id])->orderBy(['id'=>SORT_DESC]);

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
                                }])->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL,'created_uid'=>Yii::$app->user->id,'status'=>self::ORDER_STATUS_IS_PRE])->orderBy(['id'=>SORT_DESC]);

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
        $uid = Yii::$app->user->id;
        $owner = Owner::findOne($uid);
        if($owner->big_owner == Owner::IS_BIG_OWNER){
            // $category = Category::findOne($owner->category);
            // $department = Department::findOne($owner->department);
            // $category_ids = Category::find()->where(['department_id'=>$department->id])->select('id')->column();
            $query = Order::find()->with(['details'])
                              ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL])
                              ->andWhere(['category_id'=>$owner->department])
                              ->andWhere('status >= :b_status AND status <= :c_status',[':b_status'=>self::ORDER_STATUS_IS_APPROVALED,':c_status'=>self::ORDER_STATUS_IS_TRUCK])
                              ->orderBy(['id'=>SORT_DESC]);
        }else{
            $query = Order::find()->with(['details'])
                              ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL])
                              ->andWhere(['created_uid'=>$uid])
                              ->andWhere('status >= :b_status AND status <= :c_status',[':b_status'=>self::ORDER_STATUS_IS_APPROVALED,':c_status'=>self::ORDER_STATUS_IS_TRUCK])
                              ->orderBy(['id'=>SORT_DESC]);
        }

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
        $uid = Yii::$app->user->id;
        $owner = Owner::findOne($uid);
        if($owner->big_owner == Owner::IS_BIG_OWNER){
            // $category = Category::findOne($owner->category);
            $department = Department::findOne($owner->department);
            // $category_ids = Category::find()->where(['department_id'=>$department->id])->select('id')->column();
            $query = Order::find()->with(['details'])
                              ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL])
                              ->andWhere(['category_id'=>$owner->department])
                              ->andWhere(['status'=>self::ORDER_STATUS_IS_SIGN])
                              ->orderBy(['id'=>SORT_DESC]);
        }else{
            $query = Order::find()->with(['details'])
                              ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL])
                              ->andWhere(['created_uid'=>$uid])
                              ->andWhere(['status'=>self::ORDER_STATUS_IS_SIGN])
                              ->orderBy(['id'=>SORT_DESC]);
        }

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
    public static function getExcepetData($params){
        $uid = Yii::$app->user->id;
        $owner = Owner::findOne($uid);
        if($owner->big_owner == Owner::IS_BIG_OWNER){
            // $category = Category::findOne($owner->category);
            $department = Department::findOne($owner->department);
            // $category_ids = Category::find()->where(['department_id'=>$department->id])->select('id')->column();
            $query = Order::find()->with(['details'])
                              ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL])
                              ->andWhere(['category_id'=>$owner->department])
                              ->andWhere('status >= :b_status AND status <= :c_status',[':b_status'=>self::ORDER_STATUS_IS_UNSIGN,':c_status'=>self::ORDER_STATUS_IS_APPROVAL_FAIL])
                              ->orderBy(['id'=>SORT_DESC]);
        }else{
            $query = Order::find()->with(['details'])
                              ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL])
                              ->andWhere(['created_uid'=>$uid])
                              ->andWhere('status >= :b_status AND status <= :c_status',[':b_status'=>self::ORDER_STATUS_IS_UNSIGN,':c_status'=>self::ORDER_STATUS_IS_APPROVAL_FAIL])
                              ->orderBy(['id'=>SORT_DESC]);
        }

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
        $uid = Yii::$app->user->id;
        $owner = Owner::findOne($uid);
        if($owner->big_owner == Owner::IS_BIG_OWNER){
            // $category = Category::findOne($owner->category);
            // $department = Department::findOne($owner->department);
            // $category_ids = Category::find()->where(['department_id'=>$department->id])->select('id')->column();
            $query = Order::find()->with(['details'])
                              ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL,'status'=>self::ORDER_STATUS_IS_NEED_APPROVAL])
                              ->andWhere(['category_id'=>$owner->department])
                              ->orderBy(['id'=>SORT_DESC]);
        }else{
            $query = Order::find()->with(['details'])
                              ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL,'status'=>self::ORDER_STATUS_IS_NEED_APPROVAL])
                              ->andWhere(['created_uid'=>$uid])
                              ->orderBy(['id'=>SORT_DESC]);
        }
        

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
     * export done order data 
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public static function getExportDoneData($params){
        $uid = Yii::$app->user->id;
        $owner = Owner::findOne($uid);
        if($owner->big_owner == Owner::IS_BIG_OWNER){
            // $category = Category::findOne($owner->category);
            // $department = Department::findOne($owner->department);
            // $category_ids = Category::find()->where(['department_id'=>$department->id])->select('id')->column();
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
                              ->andWhere(['category_id'=>$owner->department])
                              ->orderBy(['id'=>SORT_DESC]);
        }else{
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
                              ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL,'created_uid'=>Yii::$app->user->id])
                              ->andWhere(['status'=>self::ORDER_STATUS_IS_SIGN])
                              ->orderBy(['id'=>SORT_DESC]);
        }
        
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
    public static function getSettlementData($params,$uid){
        $owner = Owner::findOne($uid);
        $category = Department::findOne($owner->department);
        if($owner){
            if($owner->storeroom->level == Storeroom::STOREROOM_LEVEL_IS_CENTER){
                if($owner->big_owner == Owner::IS_BIG_OWNER){
                    $query = Order::find()->with(['details','storeroom','createduser','tbudgetuser','package'])
                                  ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL])
                                  ->andWhere(['status'=>self::ORDER_STATUS_IS_SIGN])
                                  // ->andWhere(['storeroom_id'=>$owner->storeroom_id])
                                  //->andWhere(['budget_uid'=>$uid])
                                  ->andWhere(['category_id'=>$category->id])
                                  ->orderBy(['id'=>SORT_DESC]);
                    if (isset($params['category_id']) && $params['category_id'] != "") {
                        $uids = Owner::find()->select('id')->where(['category'=>$params['category_id']])->column();
                        $query->andWhere(['created_uid' => $uids]);
                    }

                    if (isset($params['owner_id']) && $params['owner_id'] != "") {
                        $material_ids = Material::find()->select('id')->where(['owner_id' => $params['owner_id']])->column();
                        $order_ids = OrderDetail::find()->select('distinct(order_id)')->where(['material_id' => $material_ids])->column();
                        $query->andWhere(['id' => $order_ids]);
                    }

                    if (isset($params['property']) && $params['property'] != "") {
                        $uids = Owner::find()->select('id')->where(['department' => $owner->department])->column();
                        $material_ids = Material::find()->select('id')->where(['owner_id' => $uids,'property' => $params['property']])->column();
                        $order_ids = OrderDetail::find()->select('distinct(order_id)')->where(['material_id' => $material_ids])->column();
                        $query->andWhere(['id' => $order_ids]);
                    } 

                }else{
                    $query = Order::find()->with(['details','storeroom','createduser','tbudgetuser','package'])
                                  ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL])
                                  ->andWhere(['status'=>self::ORDER_STATUS_IS_SIGN])
                                  ->andWhere(['storeroom_id'=>$owner->storeroom_id])
                                  ->andWhere(['created_uid'=>$uid])
                                  ->orderBy(['id'=>SORT_DESC]);
                }
            }else{
                if($owner->big_owner == Owner::IS_BIG_OWNER){
                    $query = Order::find()->with(['details','storeroom','createduser','tbudgetuser','package'])
                              ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL])
                              ->andWhere(['status'=>self::ORDER_STATUS_IS_SIGN])
                              // ->andWhere(['storeroom_id'=>$owner->storeroom_id])
                              ->andWhere(['category_id'=>$category->id])
                              ->orderBy(['id'=>SORT_DESC]);
                    if (isset($params['category_id']) && $params['category_id'] != "") {
                        $uids = Owner::find()->select('id')->where(['category'=>$params['category_id']])->column();
                        $query->andWhere(['created_uid' => $uids]);
                    }
                    if (isset($params['owner_id']) && $params['owner_id'] != "") {
                        $material_ids = Material::find()->select('id')->where(['owner_id' => $params['owner_id']])->column();
                        $order_ids = OrderDetail::find()->select('distinct(order_id)')->where(['material_id' => $material_ids])->column();
                        $query->andWhere(['id' => $order_ids]);
                    }

                    if (isset($params['property']) && $params['property'] != "") {
                        $uids = Owner::find()->select('id')->where(['department' => $owner->department])->column();
                        $material_ids = Material::find()->select('id')->where(['owner_id' => $uids,'property' => $params['property']])->column();
                        $order_ids = OrderDetail::find()->select('distinct(order_id)')->where(['material_id' => $material_ids])->column();
                        $query->andWhere(['id' => $order_ids]);
                    } 
                } else {
                    $query = Order::find()->with(['details','storeroom','createduser','tbudgetuser','package'])
                              ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL])
                              ->andWhere(['status'=>self::ORDER_STATUS_IS_SIGN])
                              ->andWhere(['storeroom_id'=>$owner->storeroom_id])
                              // ->andWhere(['budget_uid'=>$uid])
                              ->andWhere(['created_uid'=>$uid])
                              ->orderBy(['id'=>SORT_DESC]);
                }
                
            }
            

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
        return [];
    }
    /**
     * export done order data 
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public static function getExportSettlementData($params,$uid){
        $owner = Owner::findOne($uid);
        $category = Department::findOne($owner->department);
        if($owner){
            if($owner->storeroom->level == Storeroom::STOREROOM_LEVEL_IS_CENTER){
                if($owner->big_owner == Owner::IS_BIG_OWNER){
                    $query = Order::find()->with(['details','storeroom','createduser','tbudgetuser','package'])
                                  ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL])
                                  ->andWhere(['status'=>self::ORDER_STATUS_IS_SIGN])
                                  // ->andWhere(['storeroom_id'=>$owner->storeroom_id])
                                 // ->andWhere(['budget_uid'=>$uid])
                                  ->andWhere(['category_id'=>$category->id])
                                  ->orderBy(['id'=>SORT_DESC]);
                    if (isset($params['category_id']) && $params['category_id'] != "") {
                        $uids = Owner::find()->select('id')->where(['category'=>$params['category_id']])->column();
                        $query->andWhere(['created_uid' => $uids]);
                    }
                    if (isset($params['owner_id']) && $params['owner_id'] != "") {
                        $material_ids = Material::find()->select('id')->where(['owner_id' => $params['owner_id']])->column();
                        $order_ids = OrderDetail::find()->select('distinct(order_id)')->where(['material_id' => $material_ids])->column();
                        $query->andWhere(['id' => $order_ids]);
                    }
                    if (isset($params['property']) && $params['property'] != "") {
                        $uids = Owner::find()->select('id')->where(['department' => $owner->department])->column();
                        $material_ids = Material::find()->select('id')->where(['owner_id' => $uids,'property' => $params['property']])->column();
                        $order_ids = OrderDetail::find()->select('distinct(order_id)')->where(['material_id' => $material_ids])->column();
                        $query->andWhere(['id' => $order_ids]);
                    } 
                }else{
                    $query = Order::find()->with(['details','storeroom','createduser','tbudgetuser','package'])
                                  ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL])
                                  ->andWhere(['status'=>self::ORDER_STATUS_IS_SIGN])
                                  ->andWhere(['storeroom_id'=>$owner->storeroom_id])
                                  ->andWhere(['created_uid'=>$uid])
                                  ->orderBy(['id'=>SORT_DESC]);
                }
            }else{
                if($owner->big_owner == Owner::IS_BIG_OWNER){
                    $query = Order::find()->with(['details','storeroom','createduser','tbudgetuser','package'])
                              ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL])
                              ->andWhere(['status'=>self::ORDER_STATUS_IS_SIGN])
                              // ->andWhere(['storeroom_id'=>$owner->storeroom_id])
                              ->andWhere(['category_id'=>$category->id])
                              ->orderBy(['id'=>SORT_DESC]);
                    if (isset($params['category_id']) && $params['category_id'] != "") {
                        $uids = Owner::find()->select('id')->where(['category'=>$params['category_id']])->column();
                        $query->andWhere(['created_uid' => $uids]);
                    }
                    if (isset($params['owner_id']) && $params['owner_id'] != "") {
                        $material_ids = Material::find()->select('id')->where(['owner_id' => $params['owner_id']])->column();
                        $order_ids = OrderDetail::find()->select('distinct(order_id)')->where(['material_id' => $material_ids])->column();
                        $query->andWhere(['id' => $order_ids]);
                    }
                    if (isset($params['property']) && $params['property'] != "") {
                        $uids = Owner::find()->select('id')->where(['department' => $owner->department])->column();
                        $material_ids = Material::find()->select('id')->where(['owner_id' => $uids,'property' => $params['property']])->column();
                        $order_ids = OrderDetail::find()->select('distinct(order_id)')->where(['material_id' => $material_ids])->column();
                        $query->andWhere(['id' => $order_ids]);
                    } 
                } else {
                    $query = Order::find()->with(['details','storeroom','createduser','tbudgetuser','package'])
                              ->where(['is_del'=>Order::ORDER_IS_NOT_DEL,'can_formal'=>self::IS_FORMAL])
                              ->andWhere(['status'=>self::ORDER_STATUS_IS_SIGN])
                              ->andWhere(['storeroom_id'=>$owner->storeroom_id])
                              // ->andWhere(['budget_uid'=>$uid])
                              ->andWhere(['created_uid'=>$uid])
                              ->orderBy(['id'=>SORT_DESC]);
                }
            }
            
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
            $str = "序号,订单类型,库房位置,申请人,申请订单日期,预算所有人,起运城市,运输时效,运输类别,重量(KG),包装数量,费率,分拣费,运费,保险费,包装材料费,其他费用,总计,预估价格,发货日期,所属人,部门,组别,一级产品线,二级产品线,收货地址,收货人,订单号,备注信息\n";
            $offset = 0;
            $limit = 2000;
            $data = [];
            $i = 1;
            while(true){
                $results = $query->limit($limit)->offset($offset)->all();
                if(empty($results)){
                    break;
                }
                foreach($results as $key =>$result){
                    $data[$i]['id'] = $i;
                    if($result->type == 0 ){
                        $data[$i]['type'] = '日常订单';
                    }else{
                        $data[$i]['type'] = '大发放';
                    }
                    $data[$i]['storeroom'] = $result->storeroom->name;
                    $data[$i]['createuser'] = $result->createuser->english_name;
                    $data[$i]['created'] = date('Y-m-d H:i',strtotime($result->created));
                    $data[$i]['budgetuser'] = $result->tbudgetuser->english_name;
                    $data[$i]['storeroom_city'] = $result->storeroom->city;
                    $data[$i]['transporttype'] = $result->getMyTransportType();
                    $data[$i]['transport'] = "";
                    if(isset($result->package)){
                        if($result->package->actual_weight > $result->package->throw_weight){
                            $data[$i]['weight'] = $result->package->actual_weight;
                        }else{
                            $data[$i]['weight'] = $result->package->throw_weight;
                        }
                    }else{
                        $data[$i]['weight'] = 0;
                    }
                    
                    $data[$i]['package_num'] = $result->package->num;
                    $data[$i]['fee'] = "";
                    $data[$i]['fenjian_fee'] = $result->fenjian_fee;
                    $data[$i]['ship_fee'] = $result->real_ship_fee;
                    $data[$i]['insurance_price'] = $result->insurance_price;
                    $data[$i]['package_fee'] = $result->package->package_fee;
                    $data[$i]['other_fee'] = $result->package->other_fee;
                    $data[$i]['total'] = $result->fenjian_fee + $result->real_ship_fee + $result->insurance_price + $result->package->package_fee + $result->package->other_fee;
                    $data[$i]['pre_fee'] = $result->ship_fee + $result->fenjian_fee;
                    $data[$i]['send_time'] = date('Y-m-d H:i',$result->st_send_date);
                    $owner_ids = [];
                    foreach($result->details as $detail){
                        if(!in_array($detail->owner_id, $owner_ids)){
                            array_push($owner_ids,$detail->owner_id);
                        }
                    }
                    $data[$i]['owners'] = '';
                    $data[$i]['departments'] = '';
                    $data[$i]['categorys'] = '';
                    $data[$i]['productlines'] = '';
                    $data[$i]['producttwolines'] = '';
                    $owners = Owner::find()->with(['departments','categorys','productlines','producttwolines'])->where(['id'=>$owner_ids])->all();
                    foreach($owners as $ret){
                        $data[$i]['owners'] .= $ret->english_name."、";
                        if(isset($ret->departments)){
                            $data[$i]['departments'] .= $ret->departments->name."、";
                        }
                        if(isset($ret->categorys)){
                            $data[$i]['categorys'] .= $ret->categorys->name."、";
                        }
                        if(isset($ret->productlines)){
                            $data[$i]['productlines'] .= $ret->productlines->name."、";
                        }
                        if(isset($ret->producttwolines)){
                            $data[$i]['producttwolines'] .= $ret->producttwolines->name."、";
                        }
                    }
                    $data[$i]['owners'] = rtrim($data[$i]['owners'],'、');
                    $data[$i]['departments'] = rtrim($data[$i]['departments'],'、');
                    $data[$i]['categorys'] = rtrim($data[$i]['categorys'],'、');
                    $data[$i]['productlines'] = rtrim($data[$i]['productlines'],'、');
                    $data[$i]['producttwolines'] = rtrim($data[$i]['producttwolines'],'、');
                    $data[$i]['address'] = $result->to_province.$result->to_city.$result->to_district.$result->contact;
                    $data[$i]['user'] = $result->recipients;
                    $data[$i]['viewid'] = $result->viewid;
                    
                    $data[$i]['info'] = $result->info;
                    $str .= $data[$i]['id'].','.$data[$i]['type'].','.$data[$i]['storeroom'].','.$data[$i]['createuser'].','.$data[$i]['created'].','.$data[$i]['budgetuser'].','.$data[$i]['storeroom_city'].','.$data[$i]['transporttype'].','.$data[$i]['transport'].','.$data[$i]['weight'].','.$data[$i]['package_num'].','.$data[$i]['fee'].','.$data[$i]['fenjian_fee'].','.$data[$i]['ship_fee'].','.$data[$i]['insurance_price'].','.$data[$i]['package_fee'].','.$data[$i]['other_fee'].','.$data[$i]['total'].','.$data[$i]['pre_fee'].','.$data[$i]['send_time'].','.$data[$i]['owners'].','.$data[$i]['departments'].','.$data[$i]['categorys'].','.$data[$i]['productlines'].','.$data[$i]['producttwolines'].','.$data[$i]['address'].','.$data[$i]['user'].','.$data[$i]['viewid'].','.$data[$i]['info']."\r\n";
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
}
