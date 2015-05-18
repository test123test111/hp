<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Category extends ActiveRecord
{
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'category';
    }
}
