<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * Class live
 * @package common\models
 */
class Live extends ActiveRecord
{
    /**
     * set table name
     * @return string
     */
    public static function tableName()
    {
        return 'live';
    }
}