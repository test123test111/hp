<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class BudgetConsume extends ActiveRecord
{
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'budget_consume';
    }
    /**
     * [getConsumePriceByCategory description]
     * @param  [type] $category_id [description]
     * @return [type]              [description]
     */
    public static function getConsumePriceByCategory($category_id){
    	return static::find()->select('price')->where(['category'=>$category_id])->sum('price');
    }
}
