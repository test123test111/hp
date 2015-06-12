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
    public static function getConsumePriceByOwner($owner_id){
    	return static::find()->select('price')->where(['owner_id'=>$owner_id])->sum('price');
    }
}
