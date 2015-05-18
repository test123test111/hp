<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Department extends ActiveRecord
{
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'department';
    }
}
