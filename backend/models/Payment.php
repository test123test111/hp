<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * Class payment
 * @package common\models
 */
class Payment extends ActiveRecord
{
    /**
     * set table name
     * @return string
     */
    public static function tableName()
    {
        return 'payment';
    }
}