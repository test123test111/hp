<?php
namespace finance\controllers;

use Yii;
use backend\components\BackendController;
use common\models\DeliveryAbroad;
use common\models\Buyer;
use finance\models\search\SettlementSearch;
use common\models\Settlement;
use common\models\IgnoreBuyer;
use common\models\OrderNote;
use common\models\BuyerAccount;

class SettlementController extends BackendController{
    public $layout = false;
    const REQUEST_IS_SUCCESS = 0;
    /**
     * settlement list 
     * @return [type] [description]
     */
    public function actionList(){
        list($data,$pages,$count) = DeliveryAbroad::getSettlementList(Yii::$app->request->getQueryParams());
        return $this->render('index', [
             'results' => $data,
             'pages' => $pages,
             'count'=>$count,
             'params'=>Yii::$app->request->getQueryParams(),
             'model'=>new DeliveryAbroad,
        ]);
    }
    /**
     * action for buyer settlement list
     * @return [type] [description]
     */
    public function actionDetail(){
        if(!isset(Yii::$app->request->getQueryParams()['buyer_id'])){
            throw new \Exception("Error Processing Request", 404);
        }
        list($data,$pages,$count) = DeliveryAbroad::getOrderDetail(Yii::$app->request->getQueryParams());
        $buyerInfo = Buyer::getBuyInfoById(Yii::$app->request->getQueryParams()['buyer_id']);
        return $this->render('detail', [
             'results' => $data,
             'pages' => $pages,
             'count'=>$count,
             'params'=>Yii::$app->request->getQueryParams(),
             'buyerInfo'=>$buyerInfo,
             'model'=>new DeliveryAbroad,
        ]);
    }
    /**
     * action for ignore order 
     * @return [type] [description]
     */
    public function actionIgnore(){
        if(Yii::$app->request->isPost){
            $order_id = Yii::$app->request->post('order_id');
            $deliveryOrder = DeliveryAbroad::find()->where(['order_id'=>$order_id])->one();
            if(!empty($deliveryOrder) && $deliveryOrder->status != DeliveryAbroad::STATUS_SETTLEMENT){
                $deliveryOrder->ignoreOrder();
                echo $this->sendResponse(['rst'=>"",'errno'=>self::REQUEST_IS_SUCCESS,'err'=>'success']);
            }else{
                echo $this->sendResponse(['rst'=>"",'errno'=>DeliveryAbroad::ORDER_STATUS_IS_WRONG,'err'=>'订单状态不符合要求,操作不成功']);
            }
        }
    }
    /**
     * action for reover order 
     * @return [type] [description]
     */
    public function actionRecover(){
        if(Yii::$app->request->isPost){
            $order_id = Yii::$app->request->post('order_id');
            $deliveryOrder = DeliveryAbroad::find()->where(['order_id'=>$order_id])->one();
            if(!empty($deliveryOrder) && $deliveryOrder->status == DeliveryAbroad::STATUS_IGNORE){
                $deliveryOrder->reIgnoreOrder();
                echo $this->sendResponse(['rst'=>"",'errno'=>self::REQUEST_IS_SUCCESS,'err'=>'success']);
            }else{
                echo $this->sendResponse(['rst'=>"",'errno'=>DeliveryAbroad::ORDER_STATUS_IS_WRONG,'err'=>'订单状态不符合要求,操作不成功']);
            }
        }
    }
    /**
     * action for adjust order price 
     * @return [type] [description]
     */
    public function actionAdjust(){
        if(Yii::$app->request->isPost){
            $order_id = Yii::$app->request->post('order_id');
            $price = Yii::$app->request->post('price');
            $deliveryOrder = DeliveryAbroad::find()->where(['order_id'=>$order_id])->one();
            if(!empty($deliveryOrder) && $deliveryOrder->status != DeliveryAbroad::STATUS_SETTLEMENT){
                $deliveryOrder->adjustPrice($price);
                echo $this->sendResponse(['rst'=>"",'errno'=>self::REQUEST_IS_SUCCESS,'err'=>'success']);
            }else{
                echo $this->sendResponse(['rst'=>"",'errno'=>DeliveryAbroad::ORDER_STATUS_IS_WRONG,'err'=>'订单状态不符合要求,操作不成功']);
            }
        }
    }
    /**
     * action for 
     * @return [type] [description]
     */
    public function actionSinglepay(){
        $order_id = Yii::$app->request->get('order_id');
        $results = DeliveryAbroad::getDataGroupBuyerid($order_id);
        $order = DeliveryAbroad::find()->where(['order_id'=>$order_id])->one();
        $begin_time = $order->delivery_time;
        return $this->render('pay',['results'=>$results,'begin_time'=>$begin_time,'end_time'=>$begin_time]);
    }
    /**
     * action for batch ignore order ids 
     * @return [type] [description]
     */
    public function actionBatchignore(){
        if(Yii::$app->request->isPost){
            $order_ids = Yii::$app->request->post('order_ids');
            $order_ids = explode(',', $order_ids);
            if(!empty($order_ids)){
                DeliveryAbroad::updateAll(['status'=>DeliveryAbroad::STATUS_IGNORE],['and', 'status<>'.DeliveryAbroad::STATUS_SETTLEMENT, ['in', 'order_id', $order_ids]]);
                // echo $this->sendResponse(['rst'=>"",'errno'=>self::REQUEST_IS_SUCCESS,'err'=>'success']);
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
    }
    /**
     * action for batch adjust 
     * @return [type] [description]
     */
    public function actionBatchadjust(){
        if(Yii::$app->request->isPost){
            $price = Yii::$app->request->post('price');
            $order_ids = Yii::$app->request->post('order_ids');
            $order_ids = explode(',', $order_ids);
            if(!empty($order_ids)){
                DeliveryAbroad::updateAll(['adjust'=>$price],['and', 'status<>'.DeliveryAbroad::STATUS_SETTLEMENT, ['in', 'order_id', $order_ids]]);
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
    }
    /**
     * action for batch pay 
     * @return [type] [description]
     */
    public function actionBatchpay(){
        if(Yii::$app->request->isPost){
            $order_ids = Yii::$app->request->post('order_ids');
            $order_ids = explode(',', $order_ids);
            $begin_time = Yii::$app->request->post('begin_time');
            $end_time = Yii::$app->request->post('end_time');
            if($begin_time == "" || $end_time == ""){
                $begin_time = DeliveryAbroad::find()->where(['order_id'=>$order_ids])->orderBy(['delivery_time'=>SORT_ASC])->limit(1)->one()->delivery_time;
                $end_time = DeliveryAbroad::find()->where(['order_id'=>$order_ids])->orderBy(['delivery_time'=>SORT_DESC])->limit(1)->one()->delivery_time;
            }
            $results = DeliveryAbroad::getDataGroupBuyerid($order_ids);
            return $this->render('pay',[
                'results'=>$results,
                'begin_time'=>$begin_time,
                'end_time'=>$end_time,
            ]);
        }
    }
    /**
     * action pay to buyer 
     * @return [type] [description]
     */
    public function actionPaytobuyer(){
        $params = Yii::$app->request->getQueryParams();
        if(!isset($params['buyer_id'])){
            throw new \Exception("Error Processing Request", 404);
        }
        $order_ids = DeliveryAbroad::getAllNeedPayOrderIds(Yii::$app->request->getQueryParams());
        if(!isset($params['begin_time']) && empty($params['begin_time'])){
            $begin_time = DeliveryAbroad::find()->where(['order_id'=>$order_ids])->orderBy(['delivery_time'=>SORT_ASC])->limit(1)->one()->delivery_time;
        }else{
            $begin_time = $params['begin_time'];
        }
        if(!isset($params['end_time']) && empty($params['end_time'])){
            $end_time = DeliveryAbroad::find()->where(['order_id'=>$order_ids])->orderBy(['delivery_time'=>SORT_DESC])->limit(1)->one()->delivery_time;
        }else{
            $end_time = $params['end_time'];
        }
        $results = DeliveryAbroad::getDataGroupBuyerid($order_ids);
        return $this->render('pay',[
            'results'=>$results,
            'params'=>Yii::$app->request->getQueryParams(),
            'begin_time'=>$begin_time,
            'end_time'=>$end_time,
            ]);
    }
    /**
     * action for pay to more buyers
     * @return [type] [description]
     */
    public function actionBuyers(){
        $params = Yii::$app->request->getQueryParams();
        if((!isset($params['begin_time']) || $params['begin_time'] == "") && (!isset($params['end_time']) || $params['end_time'] == "")){
            Yii::$app->session->setFlash('info', '请按时间筛选需要结算的订单');
            return $this->redirect(Yii::$app->request->referrer);
        }  
        $results = DeliveryAbroad::getBuyersList(Yii::$app->request->getQueryParams());
        return $this->render('pay',[
            'results'=>$results,
            'params'=>$params,
            'begin_time'=>$params['begin_time'],
            'end_time'=>$params['end_time'],
        ]);
    }
    /**
     * action for clear for buyer 
     * @return [type] [description]
     */
    public function actionClear(){
        if(Yii::$app->request->isPost){
            $order_ids = Yii::$app->request->post('order_ids');
            $order_ids = explode(',', $order_ids);
            $begin_time = Yii::$app->request->post('begin_time');
            $end_time = Yii::$app->request->post('end_time');
            $result = DeliveryAbroad::checkout($order_ids,$begin_time,$end_time);
            return $this->redirect('/settlement/manual/'.$result->id);
        }
    }
    /**
     * action for history settlement list 
     * @return [type] [description]
     */
    public function actionHistory(){
        list($data,$pages,$count) = SettlementSearch::getHistoryList(Yii::$app->request->getQueryParams());
        return $this->render('history', [
             'results' => $data,
             'pages' => $pages,
             'count'=>$count,
             'params'=>Yii::$app->request->getQueryParams(),
        ]);
    }
    /**
     * action for history settlement detail 
     * @return [type] [description]
     */
    public function actionHistorydetail(){
        $id = Yii::$app->request->get('id');
        $settlement = Settlement::findOne($id);
        $results = Settlement::getDetailById($id);
        return $this->render('historydetail',[
            'results'=>$results,
            'settlement'=>$settlement,
            'settlement_id'=>$id,
        ]);
    }
    /**
     * action for ignore buyer
     * @return [type] [description]
     */
    public function actionIgnorebuyer(){
        if(Yii::$app->request->isPost){
            $buyer_id = Yii::$app->request->post('buyer_id');
            IgnoreBuyer::ignorebuyer($buyer_id);
            echo $this->sendResponse(['rst'=>[],'errno'=>self::REQUEST_IS_SUCCESS,'err'=>""]);
            // return $this->redirect(Yii::$app->request->referrer);
        }
    }
    /**
     * action for ignore batch orders
     * @return [type] [description]
     */
    public function actionIgnoreBatchOrders(){
        DeliveryAbroad::ignoreBatchOrders(Yii::$app->request->getQueryParams());
        echo $this->sendResponse(['rst'=>[],'errno'=>self::REQUEST_IS_SUCCESS,'err'=>""]);
    }
    /**
     * action note list
     * @return [type] [description]
     */
    public function actionNotelist(){
        $order_id = Yii::$app->request->post('order_id');
        $results = OrderNote::getNoteListByOrderId($order_id);
        $data = $this->renderPartial('notelist', ['results' => $results,'order_id'=>$order_id]);
        echo $this->sendResponse(['rst'=>$data,'errno'=>self::REQUEST_IS_SUCCESS,'err'=>'']);
    }
    /**
     * create note list
     * @return [type] [description]
     */
    public function actionCreatenote(){
        if(Yii::$app->request->isPost){
            $order_id = Yii::$app->request->post('order_id');
            $content = Yii::$app->request->post('content');
            $result = OrderNote::createNote($order_id,$content,OrderNote::TYPE_IS_FINANCE,'财务管理员',Yii::$app->user->id);
            if($result){
                $data = $this->renderPartial('newnote',['result'=>$result]);
                echo $this->sendResponse(['rst'=>$data,'errno'=>self::REQUEST_IS_SUCCESS,'err'=>'']);
            }else{
                echo $this->sendResponse(['rst'=>[],'errno'=>self::REQUEST_IS_FAIL,'err'=>'系统错误']);
            }
        }
    }
    /**
     * action for print history detail
     * @return [type] [description]
     */
    public function actionPrint(){
        $id = Yii::$app->request->get('id');
        $settlement = Settlement::findOne($id);
        $results = Settlement::getDetailById($id);
        return $this->renderPartial('print',['results'=>$results]);
    }
    /**
     * action for export settlement history detail 
     * @return [type] [description]
     */
    public function actionExport(){
        $id = Yii::$app->request->get('id');
        $settlement = Settlement::findOne($id);
        $results = Settlement::getDetailById($id);

        $filename = date('Y-m-d',$settlement->delivery_begin_time).'-'.date('Y-m-d',$settlement->delivery_end_time)."数据货款.csv";
        $str = "买手发货时间,订单ID,买手ID,买手名,买手真名,商品名称,SKU描述,订单金额,调整金额,包裹ID,快递公司,快递单号\n";
        foreach($results as $key=>$result){
            foreach($result['orders'] as $data){
                $goods_name = isset($data->orders->stocks) ? $data->orders->stocks->name : '';
                $sku = isset($data->orders->stocksAmount) ? $data->orders->stocksAmount->sku_value : '';
                // $package_id = isset($data->orders->pack) ? $data->orders->pack->id : '';
                $logistic_provider = isset($data->orders->pack) ? $data->orders->pack->logistic_provider : '';
                $logistic_no = isset($data->orders->pack) ? $data->orders->pack->logistic_no : '';
                $str.= date('Y-m-d H:i:s',$data->delivery_time).','.$data->order_id.','.$result['buyer_id'].','.$result['buyer_nickname'].','.$result['buyer_realname'].','.$goods_name.','.$sku.','.$data->orders->sum_price.','.$data->adjust.','.$data->orders->pack_id.','.$logistic_provider.','.$logistic_no."\n";
            }
        }
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        print(chr(0xEF).chr(0xBB).chr(0xBF));
        echo $str;
    }
    /**
     * action for check end
     * @return [type] [description]
     */
    public function actionManual($id){
        $id = Yii::$app->request->get('id');
        $settlement = Settlement::findOne($id);
        $results = Settlement::getDetailById($id);
        return $this->renderPartial('manual',['results'=>$results,'settlement_id'=>$id]);
        // return $this->render('manual');
    }
    /**
     * action 
     * @return [type] [description]
     */
    public function actionDo(){
        if(Yii::$app->request->isPost){
            $buyer_id = Yii::$app->request->post('buyer_id');
            $settlement_id = Yii::$app->request->post('id');

            $settlement = Settlement::findOne($settlement_id);
            $settlement->doSettlement($buyer_id);
            return $this->redirect("/settlement/manual/".$settlement_id);
        }
    }
    /**
     * action get buyer account
     * @return [type] [description]
     */
    public function actionBuyeraccount(){
        if(Yii::$app->request->isPost){
            $buyer_id = Yii::$app->request->post('buyer_id');
            $settlement_id = Yii::$app->request->post('id');
            $buyerInfo = Buyer::getBuyInfoById($buyer_id);
            $method = BuyerAccount::getDefaultSettlementMethod($buyerInfo->buyerAccounts);
            $data = $this->renderPartial('buyeraccount',[
                'buyerInfo'=>$buyerInfo,
                'id'=>$settlement_id,
                'buyer_id'=>$buyer_id,
                'method'=>$method,
                ]);
            echo $this->sendResponse(['rst'=>$data,'errno'=>self::REQUEST_IS_SUCCESS,'err'=>'']);
        }
    }
}