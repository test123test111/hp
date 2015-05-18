<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Buyer extends ActiveRecord
{
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'buyer';
    }
    /**
     * table buyer_account and buyer relationship
     * @return [type] [description]
     */
    public function getBuyerAccounts(){
        return $this->hasMany(BuyerAccount::className(),['buyer_id'=>'id']);
    }
    /**
     * function_description
     *
     *
     * @return
     */
    public function rules() {
        return [
            [['id','name','head'],'required'],
        ];
    }
    /**
     * [getBuyInfoById description]
     * @param  [type] $buyer_id [description]
     * @return [type]           [description]
     */
    public static function getBuyInfoById($buyer_id){
        return static::find()->with('buyerAccounts')->where(['id'=>$buyer_id])->one();
    }
}
