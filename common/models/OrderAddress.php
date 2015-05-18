<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * Class Order address
 * @package common\models
 */
class OrderAddress extends ActiveRecord
{
    /**
     * set table name
     * @return string
     */
    public static function tableName()
    {
        return 'am_order_address';
    }
}