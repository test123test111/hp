<?php
namespace customer\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
use customer\components\CustomerActiveRecord;
use common\models\Share;
use common\models\ProductLine;
use common\models\Category;
use common\models\ProductTwoLine;
use backend\models\Order;
// use backend\models\Owner;
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
    public function getOrder(){
        return $this->hasOne(Order::className(),['id'=>'order_id']);
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
        $query = Share::find()->with(['materials','storerooms','stocks','owners'=>function($query){
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
        if(isset($params['category']) && $params['category'] !=""){
            $category = Category::find()->where(['name'=>$params['category']])->one();
            if(!empty($category)){
                $query->andWhere(['category'=>$category->id]);
            }else{
                $query->andWhere(['category'=>-1]);
            }
            
        }
        if(isset($params['property']) && $params['property'] !=""){
            $query->andWhere(['property'=>$params['property']]);
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
    public static function getShareToMeData($params){
        $query = Share::find()->with(['materials','storerooms','stocks','owners'=>function($query){
                                            return $query->with('productlines');
                                        },'stockTotals'])
                              ->where(['to_customer_id'=>Yii::$app->user->id,'status'=>Share::STATUS_IS_NORMAL])
                              ->andWhere(['<>','owner_id',Yii::$app->user->id]);
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
        if(isset($params['category']) && $params['category'] !=""){
            $category = Category::find()->where(['name'=>$params['category']])->one();
            if(!empty($category)){
                $query->andWhere(['category'=>$category->id]);
            }else{
                $query->andWhere(['category'=>-1]);
            }
            
        }
        if(isset($params['property']) && $params['property'] !=""){
            $query->andWhere(['property'=>$params['property']]);
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
    public static function getExclusiveData($params){
        $query = Share::find()->with(['materials','storerooms','stocks','owners'=>function($query){
                                            return $query->with('productlines');
                                        },'stockTotals'])
                              ->where(['to_customer_id'=>Yii::$app->user->id,'status'=>Share::STATUS_IS_NORMAL])
                              ->andWhere(['owner_id'=>Yii::$app->user->id]);
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
        if(isset($params['category']) && $params['category'] !=""){
            $category = Category::find()->where(['name'=>$params['category']])->one();
            if(!empty($category)){
                $query->andWhere(['category'=>$category->id]);
            }else{
                $query->andWhere(['category'=>-1]);
            }
            
        }
        if(isset($params['property']) && $params['property'] !=""){
            $query->andWhere(['property'=>$params['property']]);
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
     * [getImportData description]
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public static function getImportData($params){
        $query = Share::find()->with(['materials','storerooms','stocks','owners'=>function($query){
                                            return $query->with(['departments','categorys','productlines','producttwolines']);
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
        $str = "序号,物料编号,物料名称,所属人,部门,组别,一级产品线,二级产品线,物料类别,入库时间,库房位置,库存数量,是否分享,物料规格,备注\n";
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
                $data[$i]['code'] = $result->materials->code;
                $data[$i]['name'] = $result->materials->name;
                $data[$i]['owner'] = $result->owners->english_name;
                $data[$i]['department'] = $result->owners->departments->name;
                $data[$i]['category'] = $result->owners->categorys->name;
                $data[$i]['productline'] = $result->owners->productlines->name;
                $data[$i]['producttwoline'] = $result->owners->producttwolines->name;
                $data[$i]['materialcategory'] = $result->materials->getMyPropertyName();
                $data[$i]['stock_time'] = date('Y-m-d H:i',strtotime($result->created));
                $data[$i]['storeroom'] = $result->storerooms->name;
                $data[$i]['total'] = $result->stockTotals->total - $result->stockTotals->lock_num;
                $countshare = Share::find()->where(['owner_id'=>$result->materials->owner_id,'status'=>Share::STATUS_IS_NORMAL])->count();
                if($countshare > 1){
                    $data[$i]['share'] = "是";
                }else{
                    $data[$i]['share'] = "否";
                }
                
                $data[$i]['package'] = $result->materials->package;
                $data[$i]['info'] = $result->materials->desc;
                $str .= $data[$i]['id'].",".$data[$i]['code'].",".$data[$i]['name'].",".$data[$i]['owner'].",".$data[$i]['department'].",".$data[$i]['category'].",".$data[$i]['productline'].",".$data[$i]['producttwoline'].",".$data[$i]['materialcategory'].",".$data[$i]['stock_time'].",".$data[$i]['storeroom'].",".$data[$i]['total'].",".$data[$i]['share'].",".$data[$i]['package'].",".$data[$i]['info']."\r\n"; //用引文逗号分开
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
    public static function getStockByUidAndStorageIdAndMaterialId($storeroom_id,$material_id){
        // $result = Stock::find()->select(['total'=>'sum(actual_quantity)'])->where(['owner_id'=>$uid,'storeroom_id'=>$storeroom_id,'material_id'=>$material_id])->groupby(['storeroom_id','material_id'])->one();
        // if(!empty($result)){
        //     return $result->total;
        // }
        // return 0;
        $total = StockTotal::find()->where(['storeroom_id'=>$storeroom_id,'material_id'=>$material_id])->one();
        if(!empty($total)){
            return $total->total;
        }
        return 0;
    }
    public static function getDetail($params){
        $query = Static::find()->with(['material','storeroom',
                                    'owners'=>function($query){
                                        return $query->with(['departments','categorys','productlines','producttwolines']);
                                    },
                                    'order'=>function($query){
                                            return $query->with('createuser');
                                     }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if(isset($params['material_id']) && $params['material_id'] != ""){
            if(isset($params['storeroom_id']) && $params['storeroom_id'] != ""){
                $material = Material::find()->where(['code'=>$params['material_id']])->one();
                if(!empty($material)){
                    $material_id = $material->id;
                    $owner_id = $material->owner_id;
                }else{
                    $material = Material::find()->where(['like','name',$params['material_id']])->one();
                    if(!empty($material)){
                        $material_id = $material->id;
                        $owner_id = $material->owner_id;
                    }else{
                        $material_id = -1;
                        $owner_id = -1;
                    }
                }
                $share = Share::find()->where(['material_id'=>$material_id,'storeroom_id'=>$params['storeroom_id'],'owner_id'=>$owner_id,'to_customer_id'=>Yii::$app->user->id,'status'=>Share::STATUS_IS_NORMAL])->one();
                if(!empty($share)){
                    $query->andWhere(['material_id'=>$share->material_id,'storeroom_id'=>$share->storeroom_id]);
                }else{
                    $query->andWhere(['material_id'=> -1,'storeroom_id'=> -1]);
                }
            }else{
                $material = Material::find()->where(['code'=>$params['material_id']])->one();
                if(!empty($material)){
                    $material_id = $material->id;
                    $owner_id = $material->owner_id;
                }else{
                    $material = Material::find()->where(['like','name',$params['material_id']])->one();
                    if(!empty($material)){
                        $material_id = $material->id;
                        $owner_id = $material->owner_id;
                    }else{
                        $material_id = -1;
                        $owner_id = -1;
                    }
                }
                $storeroom_ids = Share::find()->select('storeroom_id')->where(['material_id'=>$material_id,'owner_id'=>$owner_id,'to_customer_id'=>Yii::$app->user->id,'status'=>Share::STATUS_IS_NORMAL])->column();
                if(!empty($storeroom_ids)){
                    $query->andWhere(['material_id'=>$material_id,'storeroom_id'=>$storeroom_ids]);
                }
            }   
            if(isset($params['begin_time']) && $params['begin_time'] != ""){
                if(isset($params['end_time']) && $params['end_time'] != ""){
                    $query->andWhere('stock_time >= :begin_time AND stock_time <= :end_time',[':begin_time'=>$params['begin_time'],'end_time'=>$params['end_time']]);
                }
            }
        }else{
            if(isset($params['storeroom_id']) && $params['storeroom_id'] != ""){
                $materia_ids = Share::find()->select('material_id')->where(['storeroom_id'=>$params['storeroom_id'],'to_customer_id'=>Yii::$app->user->id,'status'=>Share::STATUS_IS_NORMAL])->column();
                if(!empty($materia_ids)){
                    $query->andWhere(['material_id'=>$materia_ids,'storeroom_id'=>$params['storeroom_id']]);
                }else{
                    $query->andWhere(['material_id'=> -1,'storeroom_id'=> -1]);
                }

                if(isset($params['begin_time']) && $params['begin_time'] != ""){
                    if(isset($params['end_time']) && $params['end_time'] != ""){
                        $query->andWhere('stock_time >= :begin_time AND stock_time <= :end_time',[':begin_time'=>$params['begin_time'],'end_time'=>$params['end_time']]);
                    }
                }
            }
        }
        
        $count = $query->count();
        $pages = new \yii\data\Pagination(['totalCount' => $count]);
        $ret = [];
        $query->offset($pages->offset)->limit(20);

        $data = $query->all();
        return [$data,$pages,$count];
    }

    public static function getExportDetail($params){
        $query = Static::find()->with(['material','storeroom',
                                    'owners'=>function($query){
                                        return $query->with(['departments','categorys','productlines','producttwolines']);
                                    },
                                    'order'=>function($query){
                                            return $query->with('createuser');
                                     }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if(isset($params['material_id']) && $params['material_id'] != ""){
            if(isset($params['storeroom_id']) && $params['storeroom_id'] != ""){
                $material = Material::find()->where(['code'=>$params['material_id']])->one();
                if(!empty($material)){
                    $material_id = $material->id;
                    $owner_id = $material->owner_id;
                }else{
                    $material = Material::find()->where(['like','name',$params['material_id']])->one();
                    if(!empty($material)){
                        $material_id = $material->id;
                        $owner_id = $material->owner_id;
                    }else{
                        $material_id = -1;
                        $owner_id = -1;
                    }
                }
                $share = Share::find()->where(['material_id'=>$material_id,'storeroom_id'=>$params['storeroom_id'],'owner_id'=>$owner_id,'to_customer_id'=>Yii::$app->user->id,'status'=>Share::STATUS_IS_NORMAL])->one();
                if(!empty($share)){
                    $query->andWhere(['material_id'=>$share->material_id,'storeroom_id'=>$share->storeroom_id]);
                }else{
                    $query->andWhere(['material_id'=> -1,'storeroom_id'=> -1]);
                }
            }else{
                $material = Material::find()->where(['code'=>$params['material_id']])->one();
                if(!empty($material)){
                    $material_id = $material->id;
                    $owner_id = $material->owner_id;
                }else{
                    $material = Material::find()->where(['like','name',$params['material_id']])->one();
                    if(!empty($material)){
                        $material_id = $material->id;
                        $owner_id = $material->owner_id;
                    }else{
                        $material_id = -1;
                        $owner_id = -1;
                    }
                }
                $storeroom_ids = Share::find()->select('storeroom_id')->where(['material_id'=>$material_id,'owner_id'=>$owner_id,'to_customer_id'=>Yii::$app->user->id,'status'=>Share::STATUS_IS_NORMAL])->column();
                if(!empty($storeroom_ids)){
                    $query->andWhere(['material_id'=>$material_id,'storeroom_id'=>$storeroom_ids]);
                }
            }   
            if(isset($params['begin_time']) && $params['begin_time'] != ""){
                if(isset($params['end_time']) && $params['end_time'] != ""){
                    $query->andWhere('stock_time >= :begin_time AND stock_time <= :end_time',[':begin_time'=>$params['begin_time'],'end_time'=>$params['end_time']]);
                }
            }
        }else{
            if(isset($params['storeroom_id']) && $params['storeroom_id'] != ""){
                $materia_ids = Share::find()->select('material_id')->where(['storeroom_id'=>$params['storeroom_id'],'to_customer_id'=>Yii::$app->user->id,'status'=>Share::STATUS_IS_NORMAL])->column();
                if(!empty($materia_ids)){
                    $query->andWhere(['material_id'=>$materia_ids,'storeroom_id'=>$params['storeroom_id']]);
                }else{
                    $query->andWhere(['material_id'=> -1,'storeroom_id'=> -1]);
                }

                if(isset($params['begin_time']) && $params['begin_time'] != ""){
                    if(isset($params['end_time']) && $params['end_time'] != ""){
                        $query->andWhere('stock_time >= :begin_time AND stock_time <= :end_time',[':begin_time'=>$params['begin_time'],'end_time'=>$params['end_time']]);
                    }
                }
            }
        }
        
        $count = $query->count();
        $str = "序号,物料编号,物料名称,所属人,部门,组别,一级产品线,二级产品线,物料类别,库房位置,入库时间,入库数量,出库时间,出库数量,出库至,申请人\n";
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
                $data[$i]['code'] = $result->material->code;
                $data[$i]['name'] = $result->material->name;
                $data[$i]['owner'] = $result->owners->english_name;
                $data[$i]['department'] = $result->owners->departments->name;
                $data[$i]['category'] = $result->owners->categorys->name;
                $data[$i]['productline'] = $result->owners->productlines->name;
                $data[$i]['producttwoline'] = $result->owners->producttwolines->name;
                $data[$i]['materialcategory'] = $result->material->getMyPropertyName();
                $data[$i]['storeroom'] = $result->storeroom->name;
                if($result->increase == 0){
                    $data[$i]['stock_time'] = date('Y-m-d H:i',strtotime($result->stock_time));
                }else{
                    $data[$i]['stock_time'] = "";
                }
                if($result->increase == 0){
                    $data[$i]['quantity'] = $result->actual_quantity;
                }else{
                    $data[$i]['quantity'] = "";
                }
                if($result->increase == 1){
                    $data[$i]['out_time'] = date('Y-m-d H:i',strtotime($result->stock_time));
                }else{
                    $data[$i]['out_time'] = "";
                }
                if($result->increase == 1){
                    $data[$i]['out_quantity'] = 0 - $result->actual_quantity;
                }else{
                    $data[$i]['out_quantity'] = "";
                }
                if($result->increase == 1){
                    if($result->order->to_type == 0 ){
                        $data[$i]['out_to'] = '收件人 '.$result->order->to_province.$result->order->to_city.$result->order->to_district.$result->order->contact;
                    }else{
                        $data[$i]['out_to'] = '平台库 '.$result->order->to_province.$result->order->to_city.$result->order->to_district.$result->order->contact;
                    }
                }else{
                    $data[$i]['out_to'] = "";
                }
                if($result->increase == 1){
                    $data[$i]['apply'] = $result->order->createuser->english_name;
                }else{
                    $data[$i]['apply'] = "";
                }
                $str .= $data[$i]['id'].",".$data[$i]['code'].",".$data[$i]['name'].",".$data[$i]['owner'].",".$data[$i]['department'].",".$data[$i]['category'].",".$data[$i]['productline'].",".$data[$i]['producttwoline'].",".$data[$i]['materialcategory'].",".$data[$i]['storeroom'].",".$data[$i]['stock_time'].",".$data[$i]['quantity'].",".$data[$i]['out_time'].",".$data[$i]['out_quantity'].",".$data[$i]['out_to'].",".$data[$i]['apply']."\r\n"; //用引文逗号分开
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