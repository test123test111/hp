<?php
namespace operate\models;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use operate\models\Order;
use operate\models\UserVip;
use operate\models\UserAddr;
class User extends ActiveRecord{

    public static function tableName(){
        return "user";
    }

    public function attributeLabels(){
        return [
            'id' => '用户ID',
            'name'=>'用户名',
            'phone'=>'手机号',
            'qq'=>'QQ',
            'weixin_id'=>'微信',
        ];
    }

    public function getVipInfo(){
        return $this->hasOne(UserVip::className(),['user_id'=>'id']);
    }

    /*
     * 取用户使用最多的手机号，从user_addr表查询
     */
    public function getUserOrderPhone(){
        return $this->hasMany(UserAddr::className(),['user_id'=>'id'])->select('cellphone')->orderBy(['count(cellphone)'=>SORT_DESC,'create_time'=>SORT_DESC])->groupBy(['cellphone'])->limit(1);
    }

    /*
     * 取用户使用最多的地址，从user_addr表查询
     */
    public function getUserOrderAddr(){
        return $this->hasMany(UserAddr::className(),['user_id'=>'id'])->select('province,city,addr')->orderBy(['count(addr)'=>SORT_DESC,'create_time'=>SORT_DESC])->groupBy(['addr'])->limit(1);
    }

    /*
     * 是否在微信群
     */
    public function getInWeixin(){
        return [
            UserVip::WEIXIN_GROUP_IN =>'是',
        ];
    }
}