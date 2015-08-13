<?php
namespace console\controllers;
use Yii;
use yii\console\Controller;
use backend\models\Order;
use backend\models\Owner;
class UpdateOrderDepartmentController extends Controller{

    /**
     * action for import alipay info to table
     * @return [type] [description]
     */
    public function actionRun()
    {
        $orders = Order::find()->all();
        foreach ($orders as $order) {
            $owner = Owner::findOne($order->created_uid);
            if (!empty($owner)) {
                $order->category_id = $owner->department;
                $order->detachBehavior('attributeStamp');
                $order->update(false);
                echo $order->id." is updated";echo "\r\n";
            }
            
        }
    }
}