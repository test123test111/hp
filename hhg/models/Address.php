<?php
namespace hhg\models;
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
        return 'hhg_address';
    }


    public function rules()
    {
        return [
            [['name', 'address','company'], 'required'],
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
     * get user company json for autocomplete
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getUserCompany($uid){
        $results = static::find()->where(['uid' => $uid])->orderBy(['status' => SORT_DESC])->all();
        $ret = [];
        foreach($results as $result){
            $array['text'] = $result->company;
            $array['url'] = '/cart/addressinfo/'.$result->id;
            array_push($ret,$array);
        }
        return json_encode($ret);
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