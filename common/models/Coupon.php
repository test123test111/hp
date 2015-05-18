<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * Class coupon
 * @package common\models
 */
class Coupon extends ActiveRecord
{
    /**
     * set table name
     * @return string
     */
    public static function tableName()
    {
        return 'coupon';
    }
    /**
     * validate coupon
     * @param  intval
     * @return true|false
     */
    public static function checkCouponValid($couponId,$user_id) {
        $coupon = static::find()->where(['coupon_id'=>$couponId,'user_id'=>$user_id])->one();
        if (empty($coupon)) {
        	return false;
        }
        //判断代金券本身状态是否有效
        if ($coupon->status !='nouse' || $coupon->expire_time<time()) {
            return false;
        }

        //代金券是否被payorder占用

        //代金券是否被order占用,等到用户全部升级到3.0的时候就不用这个了
        return $coupon;
    }
    /**
     * use coupon ,update coupon status
     * object coupon can use this function
     * @return trur|false
     */
    public function useCoupon(){
        $this->status = 'used';
        return $this->update();
    }
}