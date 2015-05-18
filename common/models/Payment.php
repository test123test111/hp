<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * Class payment
 * @package common\models
 */
class Payment extends ActiveRecord
{
	/* pay just created */
    const STATUS_CREATED = 0;

    /* pay get response true */
    const STATUS_PAY_SUCCESS = 1;

    /* pay fail */
    const STATUS_PAY_FAIL = 2;
    /**
     * set table name
     * @return string
     */
    public static function tableName()
    {
        return 'am_payment';
    }
}