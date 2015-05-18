<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * Class stock
 * @package common\models
 */
class LiveStock extends ActiveRecord
{
    /**
     * set table name
     * @return string
     */
    public static function tableName()
    {
        return 'live_stock';
    }
}