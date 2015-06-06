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
}
