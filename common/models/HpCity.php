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
    public static function getCityByPid($pro_id){
        $city = static::find()->where(['pid'=>$pro_id])->asArray()->all();
        $ret = [];
        return array_map(function($a) use ($ret){
            $ret = ['id'=>$a['id'],'name'=>$a['name']];
            return $ret;
        },$city);

        // return \yii\helpers\ArrayHelper::map($city,'id','name');
    }
    public static function getDistrictByPid($pro_id){
        $city = static::find()->where(['pid'=>$pro_id])->asArray()->all();
        $ret = [];
        return array_map(function($a) use ($ret){
            $ret = ['id'=>$a['id'],'name'=>$a['name']];
            return $ret;
        },$city);

        // return \yii\helpers\ArrayHelper::map($city,'id','name');
    }
}