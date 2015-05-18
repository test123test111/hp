<?php
namespace backend\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
use backend\components\BackendActiveRecord;
class Box extends BackendActiveRecord {
    
    const BOX_STATUS_CREATED = 0;
    const BOX_STATUS_IN = 1;
    const BOX_STATUS_PRINTED = 2;
    const BOX_STATUS_OUT = 3;


    const REQUEST_IS_SUCCESS = 0;
    const ORDER_IS_NOT_EXIST = 10001;
    const BOX_IS_NOT_EXIST = 10002;
    const ORDER_IS_IN_OTHER_BOX = 10003;
    const ORDER_IS_ALREADY_IN_BOX = 10004;
    const SYSTEM_ERROR = 10005;
    const ORDER_STATUS_WRONG = 10006;
    const BOX_STATUS_WRONG = 10007;
    const EXPRESS_ALREADY_SEND = 10008;
    const EXPRESS_ERROR = 10009;
    const BOX_OWNER_ERROR = 10010;
    const OUTPUT_ERROR = 10011;

    const EXPRESS_NUMBER_ERROR = 11009;

    const PRICE_EXPRESS_BOUNDARY = '50.00';
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'box';
    }

    /**
     * function_description
     *
     *
     * @return
     */
    public function rules() {
        return [
            [['status'],'required'],
        ];
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
                        ActiveRecord::EVENT_BEFORE_UPDATE => 'modified_time',
                    ],
                ],
                'attributeStamp' => [
                      'class' => 'yii\behaviors\AttributeBehavior',
                      'attributes' => [
                          ActiveRecord::EVENT_BEFORE_INSERT => ['created_uid','modified_uid'],
                          ActiveRecord::EVENT_BEFORE_UPDATE => 'modified_uid',
                      ],
                      'value' => function () {
                          return Yii::$app->user->id;
                      },
                ],
           ]
        );
    }
    /**
     * box and order relationships
     * @return [type] [description]
     */
    public function getBoxOrders(){
        return $this->hasMany(BoxOrder::className(),['box_id'=>'id'])->orderBy(["id"=>SORT_DESC]);
    }
    public function getUnsendBoxOrders(){
        return $this->hasMany(BoxOrder::className(),['box_id'=>'id'])->where(["status"=>BoxOrder::ORDER_IS_NOT_TO_USER])->orderBy(["id"=>SORT_DESC]);
    }
    /**
     * manager and box relationship
     * @return [type] [description]
     */
    public function getManagers(){
        return $this->hasOne(Manager::className(),['id'=>'created_uid']);
    }
    /**
     * validate order can put in box 
     * if order status not validate return false
     * if order already put in box  return false
     * .....
     * @param  int box_id
     * @param  int order_id
     * @return array([result,msg,reg_type])
     */
    public static function putOrderInBox($box_id,$order_id){
        $order = Order::findOne($order_id);
        if(empty($order)){
            return ["",'您扫描的订单不存在',self::ORDER_IS_NOT_EXIST];
        }
        //check order's status
        if(!in_array($order->status, static::getCanPutInBoxStatus())){
            return ["","订单状态不符合入库的要求",self::ORDER_STATUS_WRONG];
        }
        //check box is exist
        $box = static::findOne($box_id);
        if(empty($box)){
            return  ["","该包装箱不存在",self::BOX_IS_NOT_EXIST];
        }
        //check order is already in other box
        $boxOrder = BoxOrder::find()->where(['order_id'=>$order_id])->one();
        if(!empty($boxOrder)){
            if($boxOrder->box_id != $box_id){
                return ["","该订单已经入库到其他包装箱",self::ORDER_IS_IN_OTHER_BOX];
            }
            return ["","请勿重复扫描",self::ORDER_IS_ALREADY_IN_BOX];
        }
        $model = new BoxOrder;
        $model->order_id = $order_id;
        $model->box_id = $box_id;
        if(!$model->save(false)){
            return ["",'系统错误请再次尝试',self::SYSTEM_ERROR];
        }
        $boxOrder = BoxOrder::find()->where(['order_id'=>$order_id])->one();
        $result = yii\helpers\ArrayHelper::toArray($order,[
                "backend\models\Order"=>[
                    'order_id' => 'id',
                    'goods_name'=> function($data){
                        return $data->stocks->name;
                    },
                    'img'=>function($data){
                        $imgs = json_decode($data->stocks->imgs,true);
                        return $imgs[0];
                    },
                    'sku'=>function($data){
                        return $data->stockAmount->sku_value;
                    },
                    'box_id'=>function($data) use ($box){
                        return $box->id;
                    },
                    'created_time'=>function($data) use ($boxOrder){
                        return date('Y-m-d H:i:s',$boxOrder->created_time);
                    },
                    'created_user'=>function($data) use ($box){
                        return $box->managers->username;
                    }
                ]
            ]);
        return [$result,"扫描成功",self::REQUEST_IS_SUCCESS];
    }
    /**
     * get 
     * @return [type] [description]
     */
    public static function getCanPutInBoxStatus(){
        return [
            'wait_pay',
            'prepayed',
            'payed',
            'wait_prepay',
            'packed',
            'to_demostic',
        ];
    }
    /**
     * delete a order from box
     * @param  int box_id 
     * @param  int order_id
     * @param  update_order true:update order status to to_demostic
     * @return array([code,string])
     */
    public static function deleteOrderFromBox($box_id,$order_id,$update_order = false){
        $box = static::findOne($box_id);
        if(empty($box)){
            return  ["该包装箱不存在",self::BOX_IS_NOT_EXIST];
        }
        $result = BoxOrder::deleteAll(['order_id'=>$order_id,'box_id'=>$box_id]);
        if($update_order){
            $order = Order::findOne($order_id);
            $order->status = 'to_demostic';
            $order->save(false);

            StorageLog::saveLog($box_id,$order_id,'取消入库','国内发货','');

        }
        //update box status not display in stock page
        $count = BoxOrder::find()->where(['box_id'=>$box_id])->count();
        if($count == 0){
            $box->status = self::BOX_STATUS_CREATED;
            $box->save();
        }
        if($result){
            return ['删除成功',self::REQUEST_IS_SUCCESS];
        }
        return ['删除失败',self::SYSTEM_ERROR];
    }
    /**
     * get not send order count in box
     * object box use this function 
     * @return int 
     */
    public function getNotSendOrderCount(){
        $count = 0;
        if(isset($this->boxOrders) && !empty($this->boxOrders)){
            foreach($this->boxOrders as $boxOrder){
                if($boxOrder->status != BoxOrder::ORDER_IS_TO_USER){
                    $count ++;
                }
            }
        }
        return $count;
    }
    /**
     * get input box detail 
     * @return [type] [description]
     */
    public function getBoxDetail(){
        $ret = [];
        $ret['box_id'] =  $this->id;
        // $ret['box_created_user'] =  $this->managers->username;
        $ret['box_order_count'] = count($this->boxOrders);
        $ret['orders'] = [];
        foreach($this->boxOrders as $key=>$boxOrder){
            $ret['orders'][$key]['order_id'] = $boxOrder->orders->id;
            if(!empty($boxOrder->orders->stocks)){
                $ret['orders'][$key]['goods_name'] = $boxOrder->orders->stocks->name;
                $imgs = json_decode($boxOrder->orders->stocks->imgs,true);
                $ret['orders'][$key]['img'] = $imgs[0];
            }else{
                $ret['orders'][$key]['goods_name'] = "";
            }
            if(!empty($boxOrder->orders->stockAmount)){
                $ret['orders'][$key]['sku'] = $boxOrder->orders->stockAmount->sku_value;
            }else{
                $ret['orders'][$key]['sku'] = "";
            }
            $ret['orders'][$key]['created_time'] = date('Y-m-d H:i:s',$boxOrder->created_time);
            $ret['orders'][$key]['created_user'] = $boxOrder->managers->username;
        }
        return $ret;
    }
    /**
     * get empty box 
     * this uid is admin then display all boxes
     * is not admin only dispay boxes created by himself
     * @param  int uid
     * @param  is_admin default true
     * @return 
     */
    public static function getEmptyBoxByUid($uid,$is_admin){
        $query = Box::find()->orderBy(['id'=>SORT_DESC])->where(['status'=>self::BOX_STATUS_CREATED]);
        if(!$is_admin){
            $query->andWhere(['created_uid'=>$uid]);
        }
        return $query->all();
    }
    /**
     * get in stock boxes 
     * status is in stock or printed
     * @param  int status or empty
     * @return [type] [description]
     */
    public static function getInStockBox($status = null){
        $query = Box::find()->orderBy(['id'=>SORT_DESC]);
        if($status == null){
            $query->andWhere(['status'=>self::BOX_STATUS_IN]);
        }elseif($status == "all"){
            $query->andWhere('status = :status or status = :bstatus',[':status'=>self::BOX_STATUS_IN,":bstatus"=>self::BOX_STATUS_PRINTED]);
        }else{
            $query->andWhere(['status'=>$status]);
        }
        return $query->all();
    }
    /**
     * update order in box status is demostic
     * @return [type] [description]
     */
    public function demostic(){
        if(!empty($this->boxOrders)){
            foreach($this->boxOrders as $boxOrder){
                $order = Order::findOne($boxOrder->order_id);
                if(!empty($order)){
                    $order->status = 'demostic';
                    $order->save(false);

                    //save log
                    StorageLog::saveLog($this->id,$boxOrder->order_id,'入库','已入库');
                }
            }
        }
        return true;
    }
    /**
     * validate express number
     * return this number is shunfeng or yuantong 
     * if this number already to send return error
     * @param  intval express number
     * @return [result,code,msg]
     */
    public static function validateExpress($express_no){
        $logistic = Logistic::find()->where(['logistic_no'=>$express_no])->one();
        if(!empty($logistic)){
            return ["","对不起，您扫描的快递单已发货。请检查后重新尝试。",self::EXPRESS_ALREADY_SEND];
        }
        $express = static::getExpressCompanyByNumber($express_no);
        return [$express,"扫描成功",self::REQUEST_IS_SUCCESS];
    }
    /**
     * [getExpressCompanyByNumber description]
     * @param  [type] $express_no [description]
     * @return [type]             [description]
     */
    public static function getExpressCompanyByNumber($express_no){
        $rules = require(Yii::getAlias('@app/config/express_rule.php'));
        $length = strlen($express_no);
        $first = substr($express_no,0,1);
        foreach($rules as $key=>$rule){
            if($rule['length'] == $length && $rule['first'] == $first){
                return $key;
            }elseif($first == 2){//圆通，以2开头
                return 0;
            }else{
                continue;
            }
        }
        //default is shunfeng 
        return 0;
    }
    /**
     * get box info include box and order info
     * if order's addr is the same put them in same key
     * [
     *    'box_id'=>'1',
     *    'box_created_uid'=>1,
     *    'orders'=>[
     *        'md51'=>[
     *            0=>[
     *                'order_id'=>123,
     *                'goods_name'=>'aaa',
     *                'sku'=>'white color',
     *                'price'=>'200.00',
     *            ],
     *        'md52'=>[
 *                0=>[
     *                'order_id'=>123,
     *                'goods_name'=>'aaa',
     *                'sku'=>'white color',
     *                'price'=>'200.00',
     *            ],
     *            1=>[
     *                'order_id'=>123,
     *                'goods_name'=>'aaa',
     *                'sku'=>'white color',
     *                'price'=>'200.00',
     *            ],
 *            ],
     *            
     *            
     *            
     *        ]
     *    ] 
     * ]
     * @return [type] [description]
     */
    public function getBoxInfo(){
        $ret = [];
        $ret['box_id'] =  $this->id;
        $ret['box_created_user'] =  $this->managers->username;
        $ret['box_order_count'] = $this->getNotSendOrderCount();
        foreach($this->unsendBoxOrders as $key=>$boxOrder){
            $offset = md5($boxOrder->orders->name.$boxOrder->orders->addr);
            if(isset($ret['orders'][$offset]["sum_price"])){
                $ret['orders'][$offset]["sum_price"] += $boxOrder->orders->sum_price;
            }else{
                $ret['orders'][$offset]["sum_price"] = $boxOrder->orders->sum_price;
            }
            if($ret['orders'][$offset]["sum_price"] >= self::PRICE_EXPRESS_BOUNDARY){
                $ret['orders'][$offset]["express"] = 0;
            }else{
                $ret['orders'][$offset]["express"] = 1;
            }
            $ret['orders'][$offset]['info'][$key]['order_id'] = $boxOrder->orders->id;
            if(!empty($boxOrder->orders->stocks)){
                $ret['orders'][$offset]['info'][$key]['goods_name'] = $boxOrder->orders->stocks->name;
                $imgs = json_decode($boxOrder->orders->stocks->imgs,true);
                $ret['orders'][$offset]['info'][$key]['img'] = $imgs[0];
            }else{
                $ret['orders'][$offset]['info'][$key]['goods_name'] = "";
            }
            if(!empty($boxOrder->orders->stockAmount)){
                $ret['orders'][$offset]['info'][$key]['sku'] = $boxOrder->orders->stockAmount->sku_value;
            }else{
                $ret['orders'][$offset]['info'][$key]['sku'] = "";
            }
            $ret['orders'][$offset]['info'][$key]['stock_time'] = date('Y-m-d H:i:s',$boxOrder->created_time);
            
            $ret['orders'][$offset]["address"]['name'] = $boxOrder->orders->name;
            $ret['orders'][$offset]["address"]['cellphone'] = $boxOrder->orders->cellphone;
            $ret['orders'][$offset]["address"]['addr'] = $boxOrder->orders->province.','.$boxOrder->orders->city.','.$boxOrder->orders->addr;
        }
        return $ret;
    }
    /**
     * get template by express id
     * @param  intval express_id
     * @return string template name
     */
    public static function getTemplateByExpressId($express_id){
        $express_config = require(Yii::getAlias('@app/config/express.php'));
        return $express_config[$express_id];
    }
    /**
     * get in stock boxes 
     * status is in stock or printed
     * @param  int status or empty
     * @return [type] [description]
     */
    public static function getNeedSendBox($status = null){
        $query = Box::find()->orderBy(['id'=>SORT_DESC]);
        if($status == null){
            $query->andWhere(['status'=>self::BOX_STATUS_PRINTED]);
        }elseif($status == "all"){
            $query->andWhere('status = :status or status = :bstatus',[':status'=>self::BOX_STATUS_IN,":bstatus"=>self::BOX_STATUS_PRINTED]);
        }else{
            $query->andWhere(['status'=>$status]);
        }
        return $query->all();
    }
}