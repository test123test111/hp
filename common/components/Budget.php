<?php
namespace common\components;
use Yii;
use common\models\ShippmentCost;
use backend\models\Order;
use backend\models\OrderDetail;
use backend\models\Material;
/**
* shoppint cart class
* @author hackerone
*/
class Budget extends yii\base\Component{

	/**
	 * action for reckon order price 
	 * @param  [type] $order_id [description]
	 * @return [type]           [description]
	 */
	public function reckon($order_id){
		$order = Order::findOne($order_id);
		if(empty($order)){
			throw new \Exception("Error Processing Request", 1);
		}
		$orderDetail = OrderDetail::find()->where(['order_id'=>$order_id])->all();

		$storeroom_id = $order->storeroom_id;
		$weight = 0;
		foreach($orderDetail as $detail){
			$material = Material::findOne($detail->material_id);
			$materialWeight = $detail->quantity * $material->weight;
			$weight += $materialWeight;
		}
		$kg = ceil($weight / 1000);

		$shippment_fee = ShippmentCost::getFeeByProperty($storeroom_id,$order->to_city,$kg,$order->transport_type,$order->to_type);
		$count_detail = count($orderDetail);
		$fenjian_fee = ShippmentCost::getFenjianFee($count_detail);

		return [$shippment_fee,$fenjian_fee];
	}
}