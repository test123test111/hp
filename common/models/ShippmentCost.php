<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class ShippmentCost extends ActiveRecord
{
	const REQUEST_IS_SUCCESS = 10000;
	const SHIPPMENT_COST_TEMPLETE_NOTEXIST = 10001;
	const TRANSPORT_TYPE_NOT_EXIST = 10002;

    const TO_TYPE_IS_CUSTOMER = 0;
    const TO_TYPE_IS_PLATFORM = 1;
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'shippment_cost';
    }
    /**
     * [getFeeByProperty description]
     * @param  [type] $storeroom_id   [description]
     * @param  [type] $to_city        [description]
     * @param  [type] $weight         [description]
     * @param  [type] $transport_type [description]
     * @return [type]                 [description]
     */
    public static function getFeeByProperty($storeroom_id,$to_city,$weight,$transport_type,$to_type){
    	$templete = static::find()->where(['storeroom_id'=>$storeroom_id,'to_city'=>$to_city,'transport_type'=>$transport_type,'to_type'=>$to_type])->one();
    	if(empty($templete)){
    		return [false,0,self::SHIPPMENT_COST_TEMPLETE_NOTEXIST];
    	}
    	if($templete->fob_price == '0.00' && $templete->increase_price == '0.00'){
    		return [false,0,self::TRANSPORT_TYPE_NOT_EXIST];
    	}
    	$fob_weight = $templete->fob_weight;
    	$fob_price = $templete->fob_price;
    	$increase_weight = $templete->increase_weight;
    	$increase_price = $templete->increase_price;
    	if($weight <= $fob_weight){
    		return [true,$fob_price,self::REQUEST_IS_SUCCESS];
    	}
    	$margin = $weight - $fob_weight;
    	if($increase_weight != 0){
    		$price = $fob_price + (ceil($margin / $increase_weight) * $increase_price);
    	}else{
    		$price = $fob_price;
    	}
    	return [true,$price,self::REQUEST_IS_SUCCESS];
    }	
}
