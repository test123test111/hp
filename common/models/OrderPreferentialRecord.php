<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * Class live
 * @package common\models
 */
class OrderPreferentialRecord extends ActiveRecord
{	
	const TYPE_COUPON = 1;
	const ORDER_TYPE_IS_PAY = 0;
	const ORDER_TYPE_IS_PARENT = 1;
    /**
     * set table name
     * @return string
     */
    public static function tableName()
    {
        return 'am_order_preferential';
    }
}