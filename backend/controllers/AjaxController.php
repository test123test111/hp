<?php

namespace backend\controllers;

use Yii;
use backend\models\Material;
use backend\components\BackendController;
use backend\models\Order;

class AjaxController extends BackendController {
    public $enableCsrfValidation;
    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     */
    public function actionMarkship()
    {   
        $this->enableCsrfValidation = false;
        $id = $_POST['id'];
        $order = Order::find()->where(['id'=>$id,'is_del'=>Order::ORDER_IS_NOT_DEL])->one();
            //order shipping must package before
        if($order->status != Order::ORDER_STATUS_IS_PACKAGE){
            echo 1;
        }else{
            $order->st_send_date = time();
            $order->status = Order::ORDER_STATUS_IS_TRUCK;
            $order->save(false);
            echo 2;
        }
       
    }
    public function actionMarksign(){
        $this->enableCsrfValidation = false;
        $id = $_POST['id'];
        $order = Order::find()->where(['id'=>$id,'is_del'=>Order::ORDER_IS_NOT_DEL])->one();
        //order shipping must package before
        if($order->status != Order::ORDER_STATUS_IS_TRUCK){
            echo 1;
        }else{
            echo 2;
        }
    }
    /**
     * [actionNeworder description]
     * @return [type] [description]
     */
    public function actionNeworder(){
        echo Order::getNewOrdersCount();
    }
}
