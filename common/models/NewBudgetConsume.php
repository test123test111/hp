<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class NewBudgetConsume extends ActiveRecord
{
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'new_budget_consume';
    }
}
