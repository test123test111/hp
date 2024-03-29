<?php
namespace backend\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use backend\components\BackendActiveRecord;
use common\models\Category;
class StockTotal extends BackendActiveRecord {
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'stock_total';
    }
    /**
     * [updateTotal description]
     * @param  [type] $material_id [description]
     * @return [type]              [description]
     */
    public static function updateTotal($storeroom_id,$material_id,$count,$warning_quantity,$owner_id){
        

    	$material = self::find()->where(['material_id'=>$material_id,"storeroom_id"=>$storeroom_id])->one();
    	if(empty($material)){
            $owner = Owner::findOne($owner_id);
            $cat_id = 0;
            if($owner->category != 0){
                $category = Category::findOne($owner->category);
                $cat_id = $category->id;
            }
            $material_m = Material::findOne($material_id);

    		$model = new self;
    		$model->material_id = $material_id;
            $model->storeroom_id = $storeroom_id;
            $model->owner_id = $owner_id;
    		$model->total = $count;
            $model->created = date('Y-m-d H:i:s');
            $model->modified = date('Y-m-d H:i:s');
            $model->warning_quantity = $warning_quantity;
            $model->category = $cat_id;
            $model->property = $material_m->property;

    		$model->save();
    		return true;
    	}else{
    		$material->total = $material->total + $count;
            $material->warning_quantity = $warning_quantity;
            $material->modified = date('Y-m-d H:i:s');
    		$material->save();
    	}
    	return true;
    }
    public function getMaterial(){
    	return $this->hasOne(Material::className(),['id'=>'material_id']);
    }
    public function getStoreroom(){
        return $this->hasOne(Storeroom::className(),['id'=>'storeroom_id']);
    }
    public function getOwner(){
        return $this->hasOne(Owner::className(),['id'=>'owner_id']);
    }
    public function getLink(){
    	return '
            return \yii\helpers\Html::a("查看明细","/stock/list?StockSearch[material_id]=$model->material_id&StockSearch[storeroom_id]=$model->storeroom_id");
        ';
    }
    public function getExportLink(){
        return '
            return \yii\helpers\Html::a("导出报表","/stock/exportstock?sid=$model->storeroom_id");
        ';
    }
    public function attributeLabels(){
        return [
            'material_id'=>'物料',
            'total'=>'现有库存',
            'storeroom_id'=>'库房',
            'warning_quantity'=>'预警数量',
        ];
    }
    /**
     * [getExportDataByStoreroomId description]
     * @param  [type] $storeroom_id [description]
     * @return [type]               [description]
     */
    public static function getExportDataByStoreroomId($storeroom_id){
        $results = StockTotal::find()->where(['storeroom_id'=>$storeroom_id])->orderby(['material_id'=>SORT_DESC])->all();
        foreach($results as $key=>$result){
            $ret[$key]['store'] = $result->storeroom->name;
            $ret[$key]['material'] = $result->material->name;
            // $ret[$key]['owner'] = Owner::findOne($result->material->owner_id)->english_name;
            $ret[$key]['owner'] = Stock::findOne(['material_id'=>$result->material_id])->owners->english_name;
            $ret[$key]['total'] = $result->total;
            $ret[$key]['last_income'] = Stock::find()->where(['material_id'=>$result->material_id,'increase'=>0,'storeroom_id'=>$storeroom_id])->orderby(['id'=>SORT_DESC])->one()->stock_time;
            $ret[$key]['last_output'] = Stock::find()->where(['material_id'=>$result->material_id,'increase'=>1,'destory_reason'=>"",'storeroom_id'=>$storeroom_id])->orderby(['id'=>SORT_DESC])->one()->stock_time;
            $destory = Stock::find()->where('destory_reason!=:destory_reason',[':destory_reason'=>""])->sum('actual_quantity');
            $ret[$key]['info'] = "销毁破损".(0 - $destory);
        }
        return $ret;
    }
    //check quantity is enough 
    public static function checkQuantity($material_id,$stororoom_id,$quantity){
        $stock = static::find()->where(['material_id'=>$material_id,'storeroom_id'=>$stororoom_id])->one();
        if(empty($stock)){
            return false;
        }
        if(($stock->total - $stock->lock_num + $quantity) < $quantity){
            return false;
        }
        return true;
    }
}