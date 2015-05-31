<?php
namespace backend\models;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use common\models\ProductTwoLine as Ptl;
/**
 * Class User
 * @package common\models
 *
 */
class ProductTwoLine extends Ptl 
{   
    public function rules()
    {
        return [
            [['name','product_line_id'],'required'],
            ['name','unique'],
        ];
    }
    public function getOptLink(){
        return '
             return \yii\helpers\Html::a("编辑","/department/updateprodtwo/$model->id");
        ';
    }
    public static function getCategoryByPid($pro_id){
        $city = static::find()->where(['product_line_id'=>$pro_id])->asArray()->all();
        $ret = [];
        return array_map(function($a) use ($ret){
            $ret = ['id'=>$a['id'],'name'=>$a['name']];
            return $ret;
        },$city);
    }
}