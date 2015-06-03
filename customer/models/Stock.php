<?php
namespace customer\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
use customer\components\CustomerActiveRecord;
use common\models\Share;
use common\models\ProductLine;
use common\models\ProductTwoLine;

class Stock extends CustomerActiveRecord {
    const IS_NOT_INCREASE = 1;
    const IS_INCREASE = 0;
    public $upload;
    public $total;
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'stock';
    }

    /**
     * function_description
     *
     *
     * @return
     */
    public function rules() {
        return [
            [['material_id','storeroom_id','project_id','owner_id'],'required'],
            [['forecast_quantity','actual_quantity','stock_time','delivery'],'safe'],
            [['material_id'],'required','on'=>'search'],
        ];
    }
    public function scenarios()
    {
        return [
            'search' => ['material_id'],
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
    /**
     * [getCanUseProjects description]
     * @return [type] [description]
     */
    public function getCanUseProjects(){
        $rs = Project::find()->all();
        $arr = [];
        if($rs){
            foreach($rs as $key=>$v){
                $arr[$v['id']]=$v['name'];
            }

        }
        return $arr;
    }
    /**
     * [getCanUseProjects description]
     * @return [type] [description]
     */
    public function getCanUseMaterial(){
        $rs = Material::find()->all();
        $arr = [];
        if($rs){
            foreach($rs as $key=>$v){
                $arr[$v['id']]=$v['code']."  ".$v['name'];
            }

        }
        return $arr;
    }
    /**
     * [getCanUseProjects description]
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
    public function getStockStatus(){
        $arr = ['0'=>'入库','1'=>'出库'];
        return $arr;
    }
    public function getProjects(){
        return $this->hasOne(Project::className(),['id'=>'project_id']);
    }
    public function getStoreroom(){
        return $this->hasOne(Storeroom::className(),['id'=>'storeroom_id']);
    }
    public function getMaterial(){
        return $this->hasOne(Material::className(),['id'=>'material_id']);
    }
    public function getMymaterial(){
        return $this->hasOne(Material::className(),['id'=>'material_id']);
    }
    public function getOwners(){
        return $this->hasOne(Owner::className(),['id'=>'owner_id']);
    }
    public function getStocktotal(){
        return $this->hasOne(StockTotal::className(),['material_id'=>'material_id']);
    }
    public function getLink(){
        return '
            if($model->increase == 1){
                return "出库  ".\yii\helpers\Html::a("查看明细","/order/view?id=$model->order_id");
            }else{
                return "入库";
            }
            
        ';
    }
    /**
     * get refund data
     * @return [type] [description]
     */
    public static function getMyData($params){

        // $needData = ['material_id','created','owner_id','storeroom_id','total'=>'sum(actual_quantity)'];
        // $query = Stock::find()->select($needData)->with(['material','owners'])->where(['owner_id'=>Yii::$app->user->id])->groupby(['storeroom_id','material_id'])->orderBy(['id'=>SORT_DESC]);

        // $dataProvider = new ActiveDataProvider([
        //     'query' => $query,
        // ]);
        // if(isset($params['material_id']) && $params['material_id'] != ""){
        //     $material = Material::find()->select('id')->where(['code'=>$params['material_id']])->column();
        //     // if(!empty($material) && $material->owner_id == Yii::$app->user->id){
        //     if(empty($material)){
        //         $material = Material::find()->where(['like','name',$params['material_id']])->column();
        //     }
        //     if(empty($material)){
        //         $query->andWhere(['material_id'=>""]);
        //     }else{
        //         $query->andWhere(['material_id'=>$material]);
        //     }
            
        // }
        // if(isset($params['storeroom_id']) && $params['storeroom_id'] != ""){
        //     $query->andWhere(['storeroom_id'=>$params['storeroom_id']]);
        // }
        // if(isset($params['channel']) && $params['channel'] != ""){
        //     $material = Material::find()->where(['channel'=>$params['channel']])->one();
        //     $query->andWhere(['material_id'=>$params['material_id']]);
        // }
        // $count = $query->count();
        // $pages = new \yii\data\Pagination(['totalCount' => $count]);
        // $ret = [];
        // $query->offset($pages->offset)->limit(20);

        // $data = $query->all();
        // return [$data,$pages,$count];
        // 
        $query = Share::find()->with(['materials','storerooms','owners'=>function($query){
                                            return $query->with('productlines');
                                        },'stockTotals'])->where(['to_customer_id'=>Yii::$app->user->id,'status'=>Share::STATUS_IS_NORMAL]);
        if(isset($params['material_id']) && $params['material_id'] != ""){
            $material = Material::find()->select('id')->where(['code'=>$params['material_id']])->column();
            // if(!empty($material) && $material->owner_id == Yii::$app->user->id){
            if(empty($material)){
                $material = Material::find()->where(['like','name',$params['material_id']])->column();
            }
            if(empty($material)){
                $query->andWhere(['material_id'=>""]);
            }else{
                $query->andWhere(['material_id'=>$material]);
            }
            
        }
        if(isset($params['owner_id']) && $params['owner_id'] !=""){
            $query->andWhere(['owner_id'=>$params['owner_id']]);
        }
        if(isset($params['storeroom_id']) && $params['storeroom_id'] !=""){
            $query->andWhere(['storeroom_id'=>$params['storeroom_id']]);
        }
        if(isset($params['channel']) && $params['channel'] != ""){
            $productLine = ProductTwoLine::find()->where(['name'=>$params['channel']])->one();
            if(!empty($productLine)){
                $owners = Owner::find()->select('id')->where(['product_two_line'=>$productLine->id])->column();
                $to_customer_ids = Share::find()->select('owner_id')->where(['to_customer_id'=>Yii::$app->user->id])->column();
                $resultIds = array_intersect($owners,$to_customer_ids);
                $query->andWhere(['owner_id'=>$resultIds]);
            }else{
                $query->andWhere(['to_customer_id'=>'-1']);
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
    public static function getStockByUidAndMaterialId($uid,$material_id){
        $needData = ['storeroom_id','material_id','warning_quantity','delivery','total'=>'sum(actual_quantity)'];
        return Stock::find()->select($needData)->with(['storeroom'])->where(['owner_id'=>$uid,'material_id'=>$material_id])->groupby(['storeroom_id','material_id'])->all();
    }
    public function attributeLabels(){
        return [
            'material_id'=>'物料',
            'storeroom_id'=>'入库仓库',
            'project_id'=>'所属项目',
            'forecast_quantity'=>'预计入库数量',
            'actual_quantity'=>'实际入库数量',
            'owner_id'=>'所属人',
            'stock_time'=>'入库时间',
            'delivery'=>'送货方',
            'increase'=>'出入库标记',
            'order_id'=>'订单号',
            'created'=>'添加时间',
            'created_uid'=>'创建人',
        ];
    }
    /**
     * [getStockByUidAndStorageIdAndMaterialId description]
     * @param  [type] $uid          [description]
     * @param  [type] $storeroom_id [description]
     * @param  [type] $material_id  [description]
     * @return [type]               [description]
     */
    public static function getStockByUidAndStorageIdAndMaterialId($uid,$storeroom_id,$material_id){
        $result = Stock::find()->select(['total'=>'sum(actual_quantity)'])->where(['owner_id'=>$uid,'storeroom_id'=>$storeroom_id,'material_id'=>$material_id])->groupby(['storeroom_id','material_id'])->one();
        if(!empty($result)){
            return $result->total;
        }
        return 0;
    }
    public static function getDetail($params){
        $query = Static::find()->orderBy(['material_id'=>SORT_DESC,'id'=>SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if(isset($params['material_id']) && $params['material_id'] != ""){
            $query->andWhere(['material_id'=>$params['material_id']]);
        }       
        if(isset($params['storeroom_id']) && $params['storeroom_id'] != ""){
            $query->andWhere(['storeroom_id'=>$params['storeroom_id']]);
        }
        if(isset($params['begin_time']) && $params['begin_time'] != ""){
            if(isset($params['end_time']) && $params['end_time'] != ""){
                $query->andWhere('created_time >= :begin_time AND created_time <= :end_time',[':begin_time'=>$params['begin_time'],'end_time'=>$params['end_time']]);
            }
            
        }
        if(isset($params['material_code']) && $params['material_code'] != ""){
            $material = Material::find()->where(['code'=>$params['material_code']])->one();
            // if(!empty($material) && $material->owner_id == Yii::$app->user->id){
            if(!empty($material)){
                $query->andWhere(['material_id'=>$material->id]);
            }
        }
        $count = $query->count();
        $pages = new \yii\data\Pagination(['totalCount' => $count]);
        $ret = [];
        $query->offset($pages->offset)->limit(20);

        $data = $query->all();
        return [$data,$pages,$count];
    }

}