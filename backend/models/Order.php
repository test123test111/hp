<?php
namespace backend\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
use backend\components\BackendActiveRecord;
use backend\models\Stock;
use backend\models\OrderChannel;
use customer\models\Cart;
use common\models\Department;
use common\models\Budget;
use common\models\BudgetConsume;
use common\models\BudgetTotal;
class Order extends BackendActiveRecord {

    const NEW_ORDER = 0;
    const PACKAGE_ORDER = 1;
    const SHIPPING_ORDER = 2;
    const SIGN_ORDER = 3;
    const CONFIRM_ORDER = 4;
    const REFUSE_ORDER = 5;
    const REVOKE_ORDER = 6;
    const UNSIGN_ORDER = 7;
    const ORDER_IS_DEL = 1;
    const ORDER_IS_NOT_DEL = 0;

    const ORDER_SOURCE_CUSTOMER = 1;
    public $goods_code;
    public $goods_quantity;
    public $file;

    const BIGEST_STOREROOM_ID = 1;

    const OWNER_PASS_APPROVAL = 1;
    const OWNER_NOT_PASS_APPROVAL = 0;

    const ORDER_NEED_FEE_APPROVAL = 1;
    const ORDER_NOT_NEED_FEE_APPROVAL = 0;

    const ORDER_PASS_FEE_APPROVAL = 1;

    const IS_FORMAL = 0;
    const IS_NOT_FORMAL = 1;

    const ORDER_STATUS_IS_PRE = 0;
    const ORDER_STATUS_IS_NEED_APPROVAL = 1;
    const ORDER_STATUS_IS_APPROVALED = 2;
    const ORDER_STATUS_IS_TRUCK = 3;
    const ORDER_STATUS_IS_SIGN = 4;
    const ORDER_STATUS_IS_UNSIGN = 5;
    const ORDER_STATUS_IS_APPROVAL_FAIL = 6;



    const TO_TYPE_USER = 0;
    const TO_TYPE_PLATFORM = 1;

    const PASS_OWNER_APPROVAL = 1;
    const NOT_PASS_OWNER_APPROVAL = 0;

    const ORDER_HAVE_INSURACE = 1;
    const ORDER_HAVE_NOT_INSURANCE = 0;

    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'order';
    }

    /**
     * function_description
     *
     *
     * @return
     */
    public function rules() {
        return [
            [['storeroom_id','transport_type','to_type'],'required'],
            [['info','purpose','insurance','insurance_price','send_date','arrive_date'],'safe'],
            // ['goods_quantity',]/
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
    * Validates the password.
    * This method serves as the inline validation for password.
    */
    public function checkQuantity()
    {
        if (!$this->hasErrors()) {
            $code = $this->goods_code;
            $quantity = Stock::find()->where(['code'=>$code,'storeroom_id'=>$this->storeroom_id])->sum('actual_quantity');
            if ($this->goods_quantity > $quantiy) {
                $this->addError('goods_quantity', '库存不足.');
            }
        }
    }
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
    /**
     * [getCanUseStorerooms description]
     * @return [type] [description]
     */
    public function getCanUseGoodsByOwnerId(){
        $rs = Stock::find()->where(['owner_id'=>$this->owner_id,'storeroom_id'=>$this->storeroom_id])->all();
        $arr = [];
        if($rs){
            foreach($rs as $key=>$v){
                $arr[$v->material['code']]['code'] = $v->material['code'];
                $arr[$v->material['code']]['name'] = $v->material['name'];
                $arr[$v->material['code']]['count'] = StockTotal::find()->where(['storeroom_id'=>$this->storeroom_id,'material_id'=>$v->material_id])->one()->total;
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
    public function getCanChoseMethod(){
        $result = ["4小时"=>'4小时','12小时'=>'12小时','24小时'=>'24小时','3天'=>'3天',"5天"=>'5天'];
        return $result;
    }
    /**
     * [getCanUseStorerooms description]
     * @return [type] [description]
     */
    public function getCanUseOwner(){
        $rs = Owner::find()->all();
        $arr = [];
        if($rs){
            foreach($rs as $key=>$v){
                $arr[$v['id']]=$v['english_name'];
            }

        }
        return $arr;
    }
    /**
     * [getCanUseStorerooms description]
     * @return [type] [description]
     */
    public function getCanUseOwnerByCustomer(){
        $rs = Owner::find()->where(['id'=>Yii::$app->user->id])->all();
        $arr = [];
        if($rs){
            foreach($rs as $key=>$v){
                $arr[$v['id']]=$v['english_name'];
            }

        }
        return $arr;
    }
    public function getOptLink(){
        return '
            if($model->status == 0 || $model->status == 4){
                return \yii\helpers\Html::a("包装","/package/operate?id=$model->id");
            }elseif($model->status == 1){
                return \yii\helpers\Html::a("标记发货","#",["onclick"=>"markshipping($model->id)"]);
            }elseif($model->status == 2){
                return \yii\helpers\Html::a("标记签收<br />","#",["onclick"=>"marksign($model->id)"]).\yii\helpers\Html::a("标记未签收","#",["onclick"=>"markunsign($model->id)"]);
            }else{
                return "";
            }
        ';
    }
    public function getPrintLink(){
        return '
             return \yii\helpers\Html::a("打印<br />","/order/print?id=$model->id",["target"=>"_blank"]).\yii\helpers\Html::a("修改包装信息","/package/update?id=$model->id",["target"=>"_blank"]);
        ';
        // return 'return $model->packageInfo;';
    }
    public function getPackageInfo(){
        return $this->hasOne(Package::className(),['id'=>'package_id'])
                    ->via('orderPackage');
    }
    public function getOrderPackage(){
        return $this->hasOne(OrderPackage::className(),['order_id'=>'id']);
    }
    public function getMethodText(){
        $methods = (new Package())->getMethod();
        if(isset($methods[$this->packageInfo->method])){
            return $methods[$this->packageInfo->method];
        }
        return "undefined";
    }
    public function getChannel(){
        return $this->hasOne(Channel::className(),['connect_number'=>'connect_number'])
                    ->viaTable('order_channel',['order_id'=>'id']);
    }
    /**
     * [getOrderStatus description]
     * @return [type] [description]
     */
    public function getOrderStatus(){
        if($this->status == self::ORDER_STATUS_IS_PRE){
            return "<font color='red'>预订单<font>";
        }
        if($this->status == self::ORDER_STATUS_IS_NEED_APPROVAL){
            return "<font color='red'>待审核<font>";
        }
        if($this->status == self::ORDER_STATUS_IS_APPROVALED){
            return "<font color='red'>审核通过<font>";
        }
        if($this->status == self::ORDER_STATUS_IS_APPROVAL_FAIL){
            return "<font color='red'>审核未通过<font>";
        }
        if($this->status == self::ORDER_STATUS_IS_TRUCK){
            return "<font color='red'>已发货<font>";
        }
        if($this->status == self::ORDER_STATUS_IS_SIGN){
            return "<font color='red'>已签收<font>";
        }
        if($this->status == self::ORDER_STATUS_IS_UNSIGN){
            return "<font color='red'>未成功签收<font>";
        }
    }
    public function getLink(){
        return '
            return \yii\helpers\Html::input("text","selection[]");
        ';
    }

    public function createOrderDetail($postData,$created_uid){
        if(!is_array($postData)){
            return false;
        }
        $flag = 0;
        foreach($postData as $key=>$value){
            $model = new OrderDetail;
            $model->order_id = $this->id;
            $model->material_id = $value['material_id'];
            $model->storeroom_id = $value['storeroom_id'];
            $model->quantity = $value['quantity'];
            $material = Material::findOne($value['material_id']);
            $model->owner_id = $material->owner_id;
            if($model->owner_id == $created_uid){
                $model->is_owner_approval = OrderDetail::IS_OWNER_APPROVAL;
                $model->approval_uid = $created_uid;
                $model->approval_date = $this->created;
            }else{
                $flag ++;
            }
            $model->save();

            //Cart::deleteAll(['id'=>$cart->id]);
        }
        if($flag == 0){
            $this->owner_approval = self::PASS_OWNER_APPROVAL;
            $this->update(false);
        }
        $this->checkOrderNeedApproval();
        return true;
    }

    // public function createOrderDetail($postData,$uid){
    //     if(!is_array($postData)){
    //         return false;
    //     }
    //     foreach($postData as $key=>$value){
    //         $cart = Cart::find()->with(['storeroom','material'])->where(['id'=>$value['id']])->one();
    //         $model = new OrderDetail;
    //         $model->order_id = $this->id;
    //         $model->material_id = $cart->material->id;
    //         $model->storeroom_id = $this->storeroom_id;
    //         $model->quantity = $value['quantity'];
    //         $flag = 1;
    //         if($cart->material->owner_id != $uid){
    //             $model->owner_id = $cart->material->owner_id;
    //             $flag ++;
    //         }else{
    //             $model->owner_id = $uid;
    //             $model->is_owner_approval = OrderDetail::IS_OWNER_APPROVAL;
    //             $model->approval_uid = $uid;
    //             $model->approval_date = $this->created;
    //         }
    //         $model->save();


    //         // $stock = new Stock;
    //         // $stock->material_id = $cart->material->id;
    //         // $stock->storeroom_id = $cart->storeroom_id;
    //         // $stock->owner_id = $this->owner_id;
    //         // $stock->actual_quantity = 0 - $value['quantity'];
    //         // $stock->stock_time = date('Y-m-d H:i:s');
    //         // $stock->created = date('Y-m-d H:i:s');
    //         // $stock->increase = Stock::IS_NOT_INCREASE;
    //         // $stock->order_id = $this->id;
    //         // $stock->save(false);

    //         // //subtract stock total
    //         // StockTotal::updateTotal($stock->storeroom_id,$cart->material->id,(0 - $value['quantity']));

    //         Cart::deleteAll(['id'=>$cart->id]);
    //     }
    //     if($falg > 1){
    //         $this->owner_approval = self::OWNER_PASS_APPROVAL;
    //         $this->update();
    //     }
    //     $this->checkOrderNeedApproval();
    //     return true;
    // }
    /**
     * [checkOrderNeedApproval description]
     * @return [type] [description]
     */
    public function checkOrderNeedApproval(){
        list($ship_fee,$fenjian_fee) = Yii::$app->budget->reckon($this->id);
        $budget_fee = $ship_fee + $fenjian_fee;

        $owner = Owner::findOne($this->created_uid);
        $department_id = $owner->department;
        $department = Department::findOne($department_id);
        $storeroom = Storeroom::findOne($this->storeroom_id);
        //商用部门规则
        $total = BudgetTotal::getPriceTotalByCategory($this->created_uid);
        $consume = BudgetConsume::getConsumePriceByOwner($this->created_uid);
        if($department->id == Department::IS_COMERCIAL){
            //中央库规则
            if($storeroom->level == Storeroom::STOREROOM_LEVEL_IS_CENTER){
                if($budget_fee > ($total - $consume)){
                    $this->can_formal = self::IS_NOT_FORMAL;
                    $this->update();
                }else{
                    if(($consume / $total) < 0.85 && $budget_fee >= 1000){
                        $this->need_fee_approval = self::ORDER_NEED_FEE_APPROVAL;
                        $this->update();
                    }
                    if(($consume / $total) >= 0.85 && $budget_fee >= 500){
                        $this->need_fee_approval = self::ORDER_NEED_FEE_APPROVAL;
                        $this->update();
                    }
                }
            }else{
                if($budget_fee > ($total - $consume)){
                    $this->can_formal = self::IS_NOT_FORMAL;
                    $this->update();
                }
            }
        }
        if($department->id == Department::IS_CONSUMER){
            if($budget_fee > ($total - $consume)){
                $this->can_formal = self::IS_NOT_FORMAL;
                $this->update();
            }
        }

        if($this->owner_approval == Order::OWNER_PASS_APPROVAL && $this->need_fee_approval == Order::ORDER_NOT_NEED_FEE_APPROVAL && $this->can_formal == Order::IS_FORMAL){
            $this->status = self::ORDER_STATUS_IS_APPROVALED;
            $this->update();
            $this->consume();
        }
    }
    public function attributeLabels(){
        return [
            'goods_active'=>'活动',
            'viewid'=>'订单号',
            'storeroom_id'=>'出库仓库',
            'to_city'=>'收货城市',
            'to_province'=>'收货省份',
            'recipients'=>'收货人',
            'recipients_address'=>'收货地址',
            'recipients_contact'=>'收货人联系方式',
            'info'=>'备注',
            'limitday'=>'到货需求',
            'status'=>'订单状态',
            'created'=>'下单时间',
            'created_uid'=>'下单人',
            'file'=>'excel文件',
        ];
    }
    public function getCanChoseStatus(){
        if($this->status == 3){
            $result = [self::SIGN_ORDER=>'已签收'];
        }
        elseif($this->status == 1){
            $result = [self::SHIPPING_ORDER=>'已运输',self::SIGN_ORDER=>'已签收'];
        }
        elseif($this->status == 2){
            $result = [self::SIGN_ORDER=>'已签收'];
        }
        else {
            $package = OrderPackage::find()->where(['order_id'=>$this->id])->one();
            if(empty($package)){
                $result = [self::CONFIRM_ORDER=>'确认订单',self::REFUSE_ORDER=>"拒绝订单"];
            }else{
                $result = [self::CONFIRM_ORDER=>'确认订单',self::PACKAGE_ORDER=>'已包装',self::SHIPPING_ORDER=>'已运输',self::SIGN_ORDER=>'已签收',self::REFUSE_ORDER=>'退回订单'];
            }
        }
        return $result;
    }
    public function getCreateduser(){
        return $this->hasOne(Owner::className(), ['id' => 'created_uid']);
    }
    public function getModifieduser(){
        if($this->source == self::ORDER_SOURCE_CUSTOMER){
            return $this->hasOne(Owner::className(), ['id' => 'modified_uid']);
        }else{
            return $this->hasOne(Manager::className(), ['id' => 'modified_uid']);
        }
    }
    public function getDetails(){
        return $this->hasMany(OrderDetail::className(),['order_id'=>'id']);
    }
    public function getRevokLink(){
        return '
            if($model->status == 5){
                return \yii\helpers\Html::a("撤销订单","/order/revoke?id=$model->id",["data-method"=>"post","data-confirm"=>"撤销订单库存将自动回复"]);
            }else{
                return "";
            }
        ';
    }
    /**
     * [getCanChoseProvince description]
     * @return [type] [description]
     */
    public function getCanChoseProvince(){
        $province = City::find()->where(['pid'=>0])->all();
        $ret = ['0'=>'请选择...'];
        foreach($province as $pro){
            $ret[$pro->id] = $pro->name;
        }
        return $ret;
    }
    public function getDefaultCity(){
        return \yii\helpers\ArrayHelper::map(City::find()->where(['pid' => $this->to_province])->all(),'id','name');
    }
    /**
     * write record to budget_consume
     * @return [type] [description]
     */
    public function consume(){
        list($ship_fee,$fenjian_fee) = Yii::$app->budget->reckon($this->id);
        $price = $ship_fee + $fenjian_fee;
        $owner = Owner::findOne($this->created_uid);
        $model = new BudgetConsume;
        $model->owner_id = $this->created_uid;
        $model->category = $owner->category;
        $model->price = $price;
        $model->order_id = $this->id;
        if($model->save()){
            BudgetTotal::updateBudgetPrice($model->owner_id,$price);
        }
        //send warning email
        $total = BudgetTotal::getPriceTotalByCategory($this->created_uid);
        $consume = BudgetConsume::getConsumePriceByOwner($this->created_uid);
        if($total != 0){
            if($consume / $total >= 0.5){
                //send email 
                // Yii::$app->mail->compose('warning',['order'=>$this])
                //          ->setFrom('service@yt-logistics.cn')
                //          ->setTo($owner->email)
                //          ->setSubject("预算报警通知")
                //          ->send();
            }
        }
        
    }

}