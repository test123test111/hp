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
    public static function getPriceTotalByCategory($owner_id){
    	$total = static::find()->where(['owner_id'=>$owner_id])->one();
    	if(!empty($total)){
    		return $total->price;
    	}
    	return '0.00';
    }
    /**
     * [updateBudgetPrice description]
     * @param  [type] $owner_id [description]
     * @param  [type] $price    [description]
     * @return [type]           [description]
     */
    public static function updateBudgetPrice($owner_id,$price){
        $record = static::find()->where(['owner_id'=>$owner_id])->one();
        $price = 0 - $price;
        if(!empty($record)){
            return $record->updateCounters(['price' => $price]);
        }
    }
}
