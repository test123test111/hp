<?php
namespace customer\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use customer\components\CustomerActiveRecord;

class StockTotal extends CustomerActiveRecord {
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
    public static function updateTotal($material_id,$count){
    	$material = self::findOne($material_id);
    	if(empty($material)){
    		$model = new self;
    		$model->material_id = $material_id;
    		$model->total = $count;
    		$model->save();
    		return true;
    	}else{
    		$material->total = $material->total + $count;
    		$material->save();
    	}
    	return true;
    }
    public function getMaterial(){
    	return $this->hasOne(Material::className(),['id'=>'material_id']);
    }
    public function getLink(){
    	return '
            return \yii\helpers\Html::a("查看明细","/stock/list?StockSearch[material_id]=$model->material_id");
        ';
    }
    public function attributeLabels(){
        return [
            'material_id'=>'物料',
            'total'=>'现有库存',
        ];
    }
    //check quantity is enough 
    public static function checkQuantity($material_id,$stororoom_id,$quantity){
        $stock = static::find()->where(['material_id'=>$material_id,'stororoom_id'=>$stororoom_id])->one();
        if(empty($stock)){
            return false;
        }
        if(($stock->total - $stock->lock_num) < $quantity){
            return false;
        }
        return true;
    }
    /**
     * [getTotalNum description]
     * @param  [type] $material_id  [description]
     * @param  [type] $stororoom_id [description]
     * @return [type]               [description]
     */
    public static function getTotalNum($material_id,$storeroom_id)
    {
        $total = static::find()->where(['material_id'=>$material_id,'storeroom_id'=>$storeroom_id])->one();
        if(empty($total)){
            return 0;
        }
        return $total->warning_quantity;
    }
}