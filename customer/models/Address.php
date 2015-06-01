<?php
namespace customer\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
use customer\components\CustomerActiveRecord;

class Address extends CustomerActiveRecord {
	const USER_ADDRESS_DATA_NULL = 123123213;
    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'address';
    }


    public function rules()
    {
        return [
            [['name', 'address'], 'required'],
            [['phone', 'province', 'city', 'area', 'address', 'zip', 'tel_area_code', 'tel', 'tel_ext'], 'safe'],
        ];
    }


    /**
     * get user all address
     * @param $uid
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getUserAddress($uid)
    {
        return static::find()->where(['uid' => $uid])->orderBy(['status' => SORT_DESC])->all();
    }

    /**
     * 获取用户一个地址
     * @param $id int 地址ID
     * @param $uid int 用户ID
     * @return array|null|\yii\db\ActiveRecord 返回地址模型
     */
    public static function findOneUserAddressModel($id, $uid)
    {
        return static::find()->where(['id' => $id, 'uid' => $uid])->one();
    }
}