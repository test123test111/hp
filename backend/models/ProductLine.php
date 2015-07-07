<?php
namespace backend\models;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use common\models\ProductLine as Pl;
/**
 * Class User
 * @package common\models
 *
 */
class ProductLine extends Pl 
{	
	public function rules()
    {
        return [
        	[['name','category_id'],'required'],
        	['name','unique'],
        ];
    }
    public function getOptLink(){
        return '
             return \yii\helpers\Html::a("编辑","/department/updateprod/$model->id")."|".\yii\helpers\Html::a("设置二级产品线","/department/setting-prod-two?ProductTwoLineSearch[product_line_id]=$model->id");
        ';
    }
    /**
     * save product line for category
     * @param  [type] $produceLine [description]
     * @return [type]              [description]
     */
    public function saveProduceTwoLine($produceLine){
        $model = new ProductTwoLine;
        $model->product_line_id = $this->id;
        $model->name = $produceLine['name'];
        if($model->save()){
            return true;
        }
        return false;
    }
    public static function getCategoryByPid($pro_id){
        $city = static::find()->where(['category_id'=>$pro_id])->asArray()->all();
        $ret = [];
        return array_map(function($a) use ($ret){
            $ret = ['id'=>$a['id'],'name'=>$a['name']];
            return $ret;
        },$city);
    }
        //
}