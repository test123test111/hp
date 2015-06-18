<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class NewBudgetAdjust extends ActiveRecord
{
	public function behaviors()
    {
        return yii\helpers\BaseArrayHelper::merge(
            parent::behaviors(),
            [
                'timestamp' => [
                    'class' => 'yii\behaviors\TimestampBehavior',
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created'],
                    ],
                    'value' => function (){ return date("Y-m-d H:i:s");}
                ],
           ]
        );
    }
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'new_budget_adjust';
    }
    /**
     * table new_budget_adjust and table new_budget relationship
     * @return [type] [description]
     */
    public function getBudget(){
        return $this->hasOne(Budget::className(),['id'=>'budget_id']);
    }
    /**
     * [getAllOwnerBudgetAdjust description]
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public static function getAllOwnerBudgetAdjust($params){
        
    }
}
