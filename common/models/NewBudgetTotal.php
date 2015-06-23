<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class NewBudgetTotal extends ActiveRecord
{
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'new_budget_total';
    }
    /**
     * [updateBudgetPrice description]
     * @param  [type] $budget_id [description]
     * @param  [type] $price     [description]
     * @return [type]            [description]
     */
    public static function updateBudgetPrice($budget_id,$price){
    	$record = static::find()->where(['budget_id'=>$budget_id])->one();
        $price = 0 - $price;
        if(!empty($record)){
            return $record->updateCounters(['price' => $price]);
        }
    }	
}
