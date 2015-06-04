<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class MallCity
 * @package common\models
 */
class HpCity extends ActiveRecord
{

    public static function tableName()
    {
        return 'hp_city';
    }
}