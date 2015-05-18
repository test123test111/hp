<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * Class stock Amount
 * @package common\models
 */
class StockAmount extends ActiveRecord
{
    /**
     * set table name
     * @return string
     */
    public static function tableName()
    {
        return 'stock_amount';
    }
    /**
     * table stock_amount and table stock relationship
     * @return [type] [description]
     */
    public function getStocks(){
    	return $this->hasOne(Stock::className(),['id'=>'stock_id']);
    }
}