<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class CustomerShare extends ActiveRecord
{
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'customer_share';
    }
}
