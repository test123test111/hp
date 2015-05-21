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
             return \yii\helpers\Html::a("编辑","/department/updateprod/$model->id");
        ';
    }
}