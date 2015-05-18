<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * Class useraddr
 * @package common\models
 */
class UserAddr extends ActiveRecord
{
    /**
     * set table name
     * @return string
     */
    public static function tableName()
    {
        return 'user_addr';
    }
    
}