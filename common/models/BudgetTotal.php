<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class BudgetTotal extends ActiveRecord
{
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'budget_total';
    }
    /**
     * [getPriceTotalByCategory description]
     * @return [type] [description]
     */
    public static function getPriceTotalByCategory($category_id){
    	$total = static::find()->where(['category'=>$category_id])->one();
    	if(!empty($total)){
    		return $total->price;
    	}
    	return '0.00';
    }
}
