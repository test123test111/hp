<?php
namespace backend\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
use backend\components\BackendActiveRecord;

class Package extends BackendActiveRecord {
    const METHOD_HIGHWAY = 1;
    const METHOD_RAILWAY = 2;
    const METHOD_AIR = 3;
    const METHOD_EXPRESS = 4;
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'package';
    }

    /**
     * function_description
     *
     *
     * @return
     */
    public function rules() {
        return [
            // [['num','actual_weight','throw_weight','method','trunk','delivery','order_view_id'],'required'],
            [['num','actual_weight','throw_weight','method','order_view_id'],'required'],
            [['box','info','volume','package_fee','other_fee'],'safe'],
            // [['actual_weight','throw_weight','height','width','length'],'integer']
            [['actual_weight','throw_weight'],'integer']
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
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created', 'modified'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => 'modified',
                    ],
                    'value' => function (){ return date("Y-m-d H:i:s");}
                ],
           ]
        );
    }
    public function getPackageStatus(){
        $methods = $this->getMethod();
        return $methods[$this->method];
    }
    public function getMethod(){
        return [
            self::METHOD_EXPRESS=>'快递',
            self::METHOD_AIR=>'航空',
            self::METHOD_RAILWAY=>'铁路',
            self::METHOD_HIGHWAY=>'公路',
        ];
    }
    public function saveOrderPackage(){
        $orders = $this->order_ids;
        foreach($orders as $order){
            $orderPackage = new OrderPackage;
            $orderPackage->order_id = $order;
            $orderPackage->package_id = $this->id;
            $orderPackage->created = date('Y-m-d H:i:s');
            $orderPackage->modified = date('Y-m-d H:i:s');
            $orderPackage->save();

            $as = Order::findOne($order);
            $as->status = Order::ORDER_STATUS_IS_PACKAGE;
            $as->save(false);
        }
    }
    /**
    * Validates the password.
    * This method serves as the inline validation for password.
    */
    public function checkQuantity()
    {
        if (!$this->hasErrors()) {
            $code = $this->goods_code;
            $quantity = Stock::find()->where(['code'=>$code])->sum('actual_quantity');
            if ($this->goods_quantity > $quantiy) {
                $this->addError('goods_quantity', '库存不足.');
            }
        }
    }
    // public function beforeSave($insert){
    //     $this->volume = ($this->length * $this->width * $this->height)/(100*100*100);
    //     return parent::beforeSave($insert);
    // }
    /**
     * [getCanUseStorerooms description]
     * @return [type] [description]
     */
    public function getCanUseStorerooms(){
        $rs = Storeroom::find()->all();
        $arr = [];
        if($rs){
            foreach($rs as $key=>$v){
                $arr[$v['id']]=$v['name'];
            }

        }
        return $arr;
    }
    public function getStoreroom(){
        return $this->hasOne(Storeroom::className(),['id'=>'storeroom_id']);
    }
        /**
     * [getCanUseStorerooms description]
     * @return [type] [description]
     */
    public function getCanUseActive(){
        $rs = Stock::find()->all();
        $arr = [];
        if($rs){
            foreach($rs as $key=>$v){
                $arr[$v['active']]=$v['active'];
            }

        }
        return $arr;
    }
    public function getMethodText(){
        return '
            $methods = (new \backend\models\Package())->getMethod();
            if(isset($methods[$model->method])){
                return $methods[$model->method];
            }
            return "undefined";
        ';
    }
    public function getViewMethod(){
        $methods = (new Package())->getMethod();
        if(isset($methods[$this->method])){
            return $methods[$this->method];
        }
        return "undefined";
    }
    public function getOrders(){
        return $this->hasMany(OrderPackage::className(),['package_id'=>'id']);
    }
    public function getOrder(){
        return $this->hasMany(Order::className(),['id'=>'order_id']);
    }
    public function attributeLabels(){
        return [
            'num'=>'包装数量',
            'actual_weight'=>'实重(kg)',
            'throw_weight'=>'抛重(kg)',
            'volume'=>'体积(立方米)',
            'length'=>'长(cm)',
            'width'=>'宽(cm)',
            'height'=>'高(cm)',
            'box'=>'包装材料',
            'method'=>'运输方式',
            'trunk'=>'干线',
            'delivery'=>'派送',
            'price'=>'单价',
            'info'=>'封装',
            'created'=>'操作时间',
            'created_uid'=>'操作人',
            'package_fee'=>'包装材料费用',
            'other_fee'=>'其他费用',
            'order_id'=>'订单号',
            'order_view_id'=>'订单号',
        ];
    }
    /**
     * [getCanUseStorerooms description]
     * @return [type] [description]
     */
    public function getCanUseTrunk(){
        $rs = Trunk::find()->all();
        $arr = [];
        if($rs){
            foreach($rs as $key=>$v){
                $arr[$v['name']]=$v['name'];
            }

        }
        return $arr;
    }
    /**
     * [getCanUseStorerooms description]
     * @return [type] [description]
     */
    public function getCanUseDelivery(){
        $rs = Delivery::find()->all();
        $arr = [];
        if($rs){
            foreach($rs as $key=>$v){
                $arr[$v['name']]=$v['name'];
            }

        }
        return $arr;
    }
    public function getBox(){
        return ['A'=>'A','B'=>'B','C'=>'C','D'=>'D'];
    }
}