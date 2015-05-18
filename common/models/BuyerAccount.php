<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class BuyerAccount extends ActiveRecord
{
    const TYPE_IS_FOREIGN = 'foreign';
    const TYPE_IS_LOCAL = 'local';
    const TYPE_IS_ALIPAY = 'alipay';
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'buyer_account';
    }
    /**
     * [getMethods description]
     * @return [type] [description]
     */
    public function getMethods(){
        return [
            self::TYPE_IS_ALIPAY => '支付宝',
            self::TYPE_IS_LOCAL =>'国内银行',
            self::TYPE_IS_FOREIGN => '国外银行',
        ];
    }
    /**
     * [getObjectMethod description]
     * @return [type] [description]
     */
    public function getObjectMethod(){
        return $this->getMethods()[$this->type];
    }

    /**
     * get buyer settlement method
     * @return [type] [description]
     */
    public function getSettlementMethod(){
        return $this->getMethods()[$this->type];
    }
    /**
     * [getDefaultSettlementMethod description]
     * @param  [type] $accounts [description]
     * @return [type]           [description]
     */
    public static function getDefaultSettlementMethod($accounts){
        $types = [];
        foreach($accounts as $account){
            array_push($types, $account->type);
        }
        if(in_array(self::TYPE_IS_ALIPAY, $types)){
            return '支付宝';
        }
        if(in_array(self::TYPE_IS_LOCAL, $types)){
            return '国内银行';
        }
        if(in_array(self::TYPE_IS_FOREIGN, $types)){
            return '国外银行';
        }
        return '';
    }
}   
