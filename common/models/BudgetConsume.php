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
}
