<?php
namespace operate\models;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
class UserAddr extends ActiveRecord
{

    public static function tableName()
    {
        return "user_addr";
    }
}