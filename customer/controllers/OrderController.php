<?php

namespace customer\controllers;

use Yii;
use backend\models\Order;
use backend\models\City;
use backend\models\OrderSign;
use backend\models\Stock;
use backend\models\StockTotal;
use customer\models\OrderPackage;
use backend\models\OrderDetail;
use backend\models\Package;
use backend\models\Owner;
use backend\models\Storeroom;
use backend\models\Material;
use customer\models\search\OrderSearch;
use customer\models\search\OrderStockSearch;
use customer\components\CustomerController;
use customer\models\Address;
use common\models\HpCity;
use common\models\Approval;
use common\models\ShippmentCost;

class OrderController extends CustomerController {
    public $layout = false;
    public $enableCsrfValidation;
    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        $this->render('index');
    }
    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) echo $error['message'];
            else $this->render('error', $error);
        }
    }
    /**
     * Displays the page list
     */
    public function actionList() {
        // $searchModel = new OrderSearch;
        // $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        // return $this->render('list', [
        //     'dataProvider' => $dataProvider,
        //     'searchModel' => $searchModel,
        // ]);
        // 
        $params = Yii::$app->request->getQueryParams();
        list($data,$pages,$count) = OrderSearch::getMyData(Yii::$app->request->getQueryParams());
        $sidebar_name = '进行中的订单';
        if(isset($params['status']) && $params['status'] == OrderSearch::SIGN_ORDER){
            $sidebar_name = '已完成的订单';
        }
        return $this->render('list', [
             'results' => $data,
             'pages' => $pages,
             'count'=>$count,
             'params'=>Yii::$app->request->getQueryParams(),
             'sidebar_name'=>$sidebar_name,
        ]);
    }
    /**
     * [actionSearch description]
     * @return [type] [description]
     */
    public function actionSearch(){
        $model = new Order;
        $dataProvider = [];
        if(isset($_POST['orderid'])){
            $searchModel = new OrderSearch;
            $dataProvider = $searchModel->searchByPost($_POST['orderid']);
        }
        return $this->render("search",['model'=>$model,'dataProvider'=>$dataProvider]);
    }
    /**
     * Displays the create page
     */
    // public function actionCreate() {
    //     $model = new Order;
    //     // collect user input data
    //     if (isset($_POST['Order'])) {
    //         $model->load($_POST);
    //             $results = $model->getCanUseGoodsByOwnerId();
    //             return $this->render('create', [
    //                 'results' => $results,
    //                 'model'=>$model,
    //                 'ischange'=>true,
    //                 'owner_id'=>$_POST['Order']['owner_id'],
    //                 'storeroom_id'=>$_POST['Order']['storeroom_id'],
    //             ]);
    //     }
    //     return $this->render('create', array(
    //         'model' => $model,'isNew'=>true,'ischange'=>false,
    //     )); 
    // }
    public function actionCreate(){

    }
    /**
     * Displays the create page
     */

    public function actionCheck() {
        // $model = new Order;
        // // collect user input data
        // if(isset($_POST['confirm_end'])){
        //     $model->load($_POST);
        //     if ($model->validate()) {
        //         $db = Order::getDb();
        //         $transaction = $db->beginTransaction();
        //         try{
        //             $model->to_province = City::findOne($_POST['Order']['to_province'])->name;
        //             $model->to_city = City::findOne($_POST['Order']['to_city'])->name;
        //             $model->source = Order::ORDER_SOURCE_CUSTOMER;
        //             $model->save();
        //             $model->viewid = date('Ymd')."-".$model->id;
        //             $model->update();
        //             //create order detail 
        //             $model->createOrderDetail($_POST['OrderDetail']);
        //             $transaction->commit();
        //             $this->redirect("/order/list?OrderSearch[status]=0");
        //         }catch (\Exception $e) {
        //             $transaction->rollback();
        //             throw new \Exception($e->getMessage(), $e->getCode());
        //         }
                
        //     }
        // }
        // if (isset($_POST['selection'])) {
        //     foreach($_POST['selection'] as $key=>$value){
        //         if($value['count'] == 0){
        //             unset($_POST['selection'][$key]);
        //         }
        //     }
        //     return $this->render('checkaddress', array(
        //         'model' => $model,'isNew'=>true,'data'=>$_POST['selection'],'owner_id'=>$_POST['Order']['owner_id'],
        //             'storeroom_id'=>$_POST['Order']['storeroom_id'],
        //     )); 
        // }
    }
    // public function actionBuy() {
    //     $model = new Order;
    //     // collect user input data
    //     if(Yii::$app->request->isPost){
    //         $model->load($_POST);
    //         if ($model->validate()) {
    //             $db = Order::getDb();
    //             $transaction = $db->beginTransaction();
    //             try{
    //                 $address_id = Yii::$app->request->post('address');
    //                 $address = Address::findOne($address_id);
    //                 $model->to_province = $address->province;
    //                 $model->to_city = $address->city;
    //                 $model->source = Order::ORDER_SOURCE_CUSTOMER;
    //                 $model->save();
    //                 $model->viewid = date('Ymd')."-".$model->id;
    //                 $model->update();
    //                 //create order detail 
    //                 $model->createOrderDetail($_POST['Carts']);
    //                 $transaction->commit();
    //                 $this->redirect("/order/success?id={$model->viewid}");
    //             }catch (\Exception $e) {
    //                 $transaction->rollback();
    //                 throw new \Exception($e->getMessage(), $e->getCode());
    //             }
                
    //         }
    //     }
    // }
    public function actionBuy() {
        $model = new Order;
        // collect user input data
        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
            $db = Order::getDb();
            $transaction = $db->beginTransaction();
            try{
                if($model->to_type == Order::TO_TYPE_USER){
                    $address_id = Yii::$app->request->post('address');
                    $address = Address::findOne($address_id);
                    $model->to_company = $address->company;
                    $model->to_province = $address->province;
                    $model->to_city = $address->city;
                    $model->to_district = $address->area;
                    $model->phone = $address->phone;
                    $model->recipients = $address->name;
                    $model->contact = $address->address;
                }else{
                    $storeroom = Yii::$app->request->post('storeroom');
                    $record = Storeroom::findOne($storeroom);
                    $province = HpCity::findOne($record->province);
                    $model->to_province = $province->name;
                    $city = HpCity::findOne($record->city);
                    $model->to_city = $city->name;
                    $district = HpCity::findOne($record->district);
                    if(!empty($district)){
                        $model->to_district = $district->name;
                    }else{
                        $model->to_district = "";
                    }
                    $model->phone = $record->phone;
                    $model->recipients = $record->contact;
                    $model->contact = $record->address;
                }
                if($model->insurance_price == ''){
                    $model->insurance_price = '0.00';
                }
                if($model->arrive_date != ''){
                    $model->arrive_date = strtotime($model->arrive_date);
                }else{
                    $model->arrive_date = 0;
                }
                $model->save();
                $model->viewid = date('Ymd')."-".$model->id;
                $model->update();
                //create order detail 
                $model->createOrderDetail($_POST['Carts'],Yii::$app->user->id);
                $transaction->commit();
                $this->redirect("/order/success?id={$model->viewid}");
            }catch (\Exception $e) {
                $transaction->rollback();
                throw new \Exception($e->getMessage(), $e->getCode());
            }
                
        }
    }
    /**
     * Displays the create page
     */
    public function actionUpdate($id) {
        $model = new Order;
        $id = $_GET['id'];
        if($id){
            $model = $this->loadModel($id);
            if (!empty($_POST)) {
                if ($model->load($_POST) && $model->save()) {
                    Yii::$app->session->setFlash('success', '修改成功!');
                    return $this->redirect(Yii::$app->request->getReferrer());
                }
            }
        }
        return $this->render('create',['model'=>$model,'isNew'=>false]);
    }
    /**
     * Displays the create page
     */
    public function actionRevoke($id) {
        if($id){
            $model = $this->loadModel($id);
            if($model->status == Order::REFUSE_ORDER){
                $model->status = Order::REVOKE_ORDER;
                $db = Order::getDb();
                $transaction = $db->beginTransaction();
                try{
                    // if($model->save()){
                        $model->save();
                        //Recovery inventory
                        //delete stock about this order id the recovery stock total
                        Stock::deleteAll(['order_id'=>$id]);
                        $details = OrderDetail::find()->where(['order_id'=>$id])->all();
                        if(!empty($details)){
                            foreach($details as $detail){
                                $storeroom_id = Order::findOne($id)->storeroom_id;
                                $material_id = Material::find()->where(['code'=>$detail->goods_code])->one()->id;
                                $total = StockTotal::find(['storeroom_id'=>$storeroom_id,'material_id'=>$material_id])->one();
                                if(!empty($total)){
                                    $total->total = $total->total + $detail->goods_quantity;
                                    if(!$total->update()){
                                        throw new \Exception("Error Processing Request", 1);
                                    }
                                }
                            }
                        }
                    // }
                    $transaction->commit();
                }catch (\Exception $e) {
                    $transaction->rollback();
                    throw new \Exception($e->getMessage(), $e->getCode());
                }
                
            }
        }
        return $this->redirect(Yii::$app->request->getReferrer());
    }
    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $order = Order::find()->with('details')->where(['id'=>$id,'is_del'=>Order::ORDER_IS_NOT_DEL])->one();
        list($ship_fee,$fenjian_fee) = Yii::$app->budget->reckon($order->id);
        return $this->render('view', [
            'order' => $order,
            'ship_fee'=>$ship_fee,
            'fenjian_fee'=>$fenjian_fee,
        ]);
    }
    public function actionChange(){
        if(isset($_POST['orderid'])){
            $order = Order::findOne($_POST['orderid']);
            if(!empty($order)){
                $order->status = $_POST['status'];
                $order->save(false);
                $this->redirect('/order/view?id='.$order->id);
            }
        }
    }
    /**
     * [actionPrint description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionPrint($id){
        $order = Order::find()->where(['id'=>$id,'is_del'=>Order::ORDER_IS_NOT_DEL])->one();
        $detail = OrderDetail::find()->where(['order_id'=>$id])->all();
        return $this->renderPartial('print', [
            'order' => $order,
            'detail' =>$detail,
        ]);
    }
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $order = Order::find()->where(['id'=>$id,'is_del'=>Order::ORDER_IS_NOT_DEL])->one();
        if ($order === null) throw new CHttpException(404, 'The requested page does not exist.');
        
        return $order;
    }
    public function actionGetgoods(){
        $this->enableCsrfValidation = false;
        $result = Order::getCanUseGoodsByOwnerId($_POST['owner_id'],$_POST['storeroom_id']);
        echo json_encode($result);
    }
    public function actionImport(){
        $model = new Order;
        $error = [];
        $right = false;
        if(isset($_POST['Order']) && $_POST['Order'] != ""){
            $objPHPExcel = new \PHPExcel();
            $objPHPExcel = \PHPExcel_IOFactory::load($_FILES["Order"]["tmp_name"]['file']);
            $datas = $objPHPExcel->getSheet(0)->toArray();
            $ret = [];
            // $datas = [
            //     ['序号','收件人','收件地址','收件人电话','收件城市','活动','发货仓库','物料编码','物料属主','数量','到货需求','备注'],
            //     ['1','','','','','','北京中央库','JIHFSN899011','alisa','20','4小时','must be'],
            //     ['1','wanglei','beijing office','13800138000','beijing','2014 word cup','北京中央库','GSDGSG99990SGA','alisa','5','4小时','must be'],
            //     ['2','lisi','beijing office','13800138000','beijing','2014 word cup','北京中央库','JIHFSN899011','alisa','40','4小时','must be'],
            // ];
            foreach($datas as $key=>$data){
                if($key == 0){
                    continue;
                }else{
                    $ret[$data[0]]['recipients'] = $data[1];
                    $ret[$data[0]]['recipients_address'] = $data[2];  
                    $ret[$data[0]]['recipients_contact'] = $data[3];  
                    $ret[$data[0]]['to_city'] = $data[4];
                    $ret[$data[0]]['goods_active'] = $data[5];
                    $ret[$data[0]]['storeroom_id'] = $data[6];
                    $ret[$data[0]]['goods'][$data[7]] = $data[9];
                    // $ret[$data[0]]['goods']['count'][] = $data[9];
                    $ret[$data[0]]['owner_id'] = $data[8];
                    $ret[$data[0]]['limitday'] = $data[10];
                    $ret[$data[0]]['info'] = $data[11];
                }
            }
            $error = $this->checkOrderRight($ret);
            if(!empty($error)){
                if(!isset($error['owner_error'])){
                    $error['owner_error'] = [];
                }
                if(!isset($error['material_error'])){
                    $error['material_error'] = [];
                }
                if(!isset($error['storeroom_error'])){
                    $error['storeroom_error'] = [];
                }
                if(!isset($error['total_error'])){
                    $error['total_error'] = [];
                }
            }else{
                if($this->createBatchOrder($ret)){
                    $right = true;
                }else{
                    $right = false;
                }
                
            }
        }
        return $this->render('import',['model'=>$model,'error'=>$error,'right'=>$right]);
    }
    /**
     * [checkOrderRight description]
     * @param  [type] $result [description]
     * @return [type]         [description]
     */
    public function checkOrderRight($orderArray){
        $error = [];
        $count = [];
        $num = 0;
        foreach($orderArray as $value){
            $storeroom_id = $value['storeroom_id'];
            
            $storeroom = Storeroom::find()->where(['name'=>trim($value['storeroom_id'])])->one();
            $owner = Owner::find()->where(['english_name'=>$value['owner_id']])->one();
            if(empty($storeroom)){
                $error['storeroom_error'][$value['storeroom_id']] = $value['storeroom_id'];
            }elseif(empty($owner)){
                $error['owner_error'][$value['owner_id']] = $value['owner_id'];
            }else{
                foreach($value['goods'] as $key=>$v){
                    $material = Material::find()->where(['code'=>$key])->one();
                    if(empty($material)){
                        $error['material_error'][$key] = $key; 
                    }else{
                        if(isset($count[$key])){
                            $count[$key] += $v;
                        }else{
                            $count[$key] = $v;
                        }
                    }
                }
                foreach($count as $k=>$v1){
                    $material = Material::find()->where(['code'=>$k])->one();
                    $stock_total = StockTotal::find()->where(['storeroom_id'=>$storeroom->id,'material_id'=>$material->id])->one();
                    if($stock_total->total < $v1){
                        $error['total_error'][$k] = $k;
                    }
                }
            }
        }
        return $error;
    }
    public function actionSuccess(){
        $id = Yii::$app->request->get('id');
        return $this->render('success',['id'=>$id]);
    }
    /**
     * [createOrder description]
     * @param  [type] $orderArray [description]
     * @return [type]             [description]
     */
    protected function createBatchOrder($orderArray){
        $db = Owner::getDb();
        $transaction = $db->beginTransaction();
        try {
            foreach($orderArray as $value){
                $storeroom = Storeroom::find()->where(['name'=>trim($value['storeroom_id'])])->one();
                $owner = Owner::find()->where(['english_name'=>$value['owner_id']])->one();
                //create order
                $model = new Order;
                $model->goods_active = $value['goods_active'];
                $model->storeroom_id = $storeroom->id;
                $model->owner_id = $owner->id;
                $model->to_city = $value['to_city'];
                $model->recipients = $value['recipients'];
                $model->recipients_address = $value['recipients_address'];
                $model->recipients_contact = $value['recipients_contact'];
                $model->info = $value['info'];
                $model->limitday = $value['limitday'];
                $model->created = date('Y-m-d H:i:s');
                $model->created_uid = Yii::$app->user->id;
                $model->source = Order::ORDER_SOURCE_CUSTOMER;
                $model->save(false);
                $model->viewid = date('Ymd')."-".$model->id;
                $model->update();

                foreach($value['goods'] as $key=>$v){
                    $detail = new OrderDetail;
                    $detail->order_id = $model->id;
                    $detail->goods_code = $key;
                    $detail->goods_quantity = $v;
                    $detail->save();
                }
                //Subtract stock
                foreach($value['goods'] as $key=>$v){
                    $material = Material::find()->where(['code'=>$key])->one();
                    $stock = new Stock;
                    $stock->material_id = $material->id;
                    $stock->storeroom_id = $model->storeroom_id;
                    $stock->owner_id = $model->owner_id;
                    $stock->project_id = $material->project_id;
                    $stock->actual_quantity = 0 - $v;
                    $stock->stock_time = date('Y-m-d H:i:s');
                    $stock->created = date('Y-m-d H:i:s');
                    $stock->increase = Stock::IS_NOT_INCREASE;
                    $stock->order_id = $model->id;
                    $stock->active = $model->goods_active;
                    $stock->save(false);

                    //subtract stock total
                    // StockTotal::updateTotal($model->storeroom_id,$material->id,(0 - $v));
                    $stockTotal = StockTotal::find()->where(['material_id'=>$material->id,"storeroom_id"=>$model->storeroom_id])->one();
                    $stockTotal->total = $stockTotal->total + (0 - $v);
                    $stockTotal->update();
                }
            }
            //create order detail
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
    public function actionReport(){
        $data = [];
        $pages = [];
        $count = 0;
        if(isset($_GET['search']) && $_GET['search'] ==1 ){
            list($data,$pages,$count) = OrderSearch::getMyData(Yii::$app->request->getQueryParams());
        }
        return $this->render('report', [
                 'results' => $data,
                 'pages' => $pages,
                 'count'=>$count,
                 'params'=>Yii::$app->request->getQueryParams(),
            ]);
    }
    public function actionCity(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $cat_id = $parents[0];
                $param1 = null;
                $param2 = null;
                if (!empty($_POST['depdrop_params'])) {
                    $params = $_POST['depdrop_params'];
                    $param1 = $params[0]; // get the value of input-type-1
                    $param2 = $params[1]; // get the value of input-type-2
                }
     
                // $out = self::getSubCatList1($cat_id, $param1, $param2); 
                // the getSubCatList1 function will query the database based on the
                // cat_id, param1, param2 and return an array like below:
                $out = City::getCityByPid($cat_id);
                // var_dump($out);exit;
                // var_dump($out);exit;
                // $out = [
                //        ['id'=>'20', 'name'=>'a'],
                //        ['id'=>'21', 'name'=>'b'],
                //        ['id'=>'22', 'name'=>'c'], 
                //        ['id'=>'23', 'name'=>'d'],
                // ];
                
                
                // $selected = self::getDefaultSubCat($cat_id);
                // the getDefaultSubCat function will query the database
                // and return the default sub cat for the cat_id
                echo json_encode(['output'=>$out, 'selected'=>$out[0]['id']]);
                return;
            }
        }
        echo json_encode(['output'=>'', 'selected'=>'']);
    }
    /**
     * action for user add address or modify address
     * if post data has address id is modigy else create new address
     * @throws \yii\web\HttpException
     */
    public function actionAddress()
    {
        if (!Yii::$app->request->isPost) {
            throw new \yii\web\HttpException(404, 'The requested page does not exist.');
        }
        if ($_POST['Address']) {
            if (!Yii::$app->request->post('id')) {
                $model = new Address;
            } else {
                $model = Address::findOne(Yii::$app->request->post('id'));
            }
            $model->load($_POST);
            $model->uid = Yii::$app->user->id;
            if ($model->isNewRecord) {
                $address = Address::find()->where(['uid' => Yii::$app->user->id, 'status' => 1])->one();
                if (!empty($address)) {
                    $address->status = 0;
                    $address->save(false);
                }
                $model->status = 1;
            }
            if ($model->validate() && $model->save()) {
                // $userAddress = Address::getUserAddress(Yii::$app->user->id);
                $userAddress = Address::findOne($model->id);
                echo $this->renderPartial('addresslist', ['addr' => $userAddress]);
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }
    /**
     * address list
     * @throws \yii\web\HttpException
     */
    public function actionAddressdisplay()
    {
        if (!Yii::$app->request->isPost) {
            throw new HttpException(404, 'The requested page does not exist.');
        }
        if (Yii::$app->request->post('id')) {
            $model = Address::findOne(Yii::$app->request->post('id'));
            echo $this->renderPartial('addressdisplay', ['model' => $model, 'isNew' => false]);
        } else {
            $model = new Address;
            echo $this->renderPartial('addressdisplay', ['model' => $model, 'isNew' => true]);
        }
    }
    //物主审批物料(批量审批)
    public function actionApprovalmaterial(){
        if(Yii::$app->request->isPost){
            // $order_id = Yii::$app->request->post('order_id');
            $orderInfo = Order::findOne($order_id);
            // $material_ids = Yii::$app->request->post('material_ids');
            // $details = OrderDetail::find()->where(['order_id'=>$order_id,'material_id'=>$material_ids,'owner_id'=>Yii::$app->user->id])->all();
            // foreach($details as $detail){
            //     $detail->is_owner_approval = OrderDetail::IS_OWNER_APPROVAL;
            //     $detail->approval_uid = Yii::$app->user->id;
            //     $detail->approval_date = date('Y-m-d H:i:s');
            //     $detail->update();
            // }
            // $orders = OrderDetail::find()->where(['order_id'=>$order_id])->all();
            // $flag = 0;
            // foreach($orders as $order){
            //     if($order->is_owner_approval == OrderDetail::IS_OWNER_APPROVAL){
                    
            //     }else{
            //         $flag ++;
            //     }
            // }
            // if($flag == 0){
            //     Order::updateAll(['owner_approval'=>Order::OWNER_PASS_APPROVAL],['id'=>$order_id]);
            // }
            // if($orderInfo->owner_approval == Order::OWNER_PASS_APPROVAL && $orderInfo->fee_approval == Order::ORDER_PASS_FEE_APPROVAL && $orderInfo->can_formal == Orde::IS_FORMAL){
            //     $orderInfo->status = Order::ORDER_STATUS_IS_APPROVALED;
            //     $orderInfo->update();

            //     $orderInfo->consume();
            // }
            $order_id = Yii::$app->request->post('order_id');
            $approval = Approval::find()->where(['order_id'=>$order_id,'owner_id'=>Yii::$app->user->id])->one();
            $details = OrderDetail::find()->where(['order_id'=>$order_id,'owner_id'=>Yii::$app->user->id])->all();
            if(!empty($details)){
                foreach($details as $detail){
                    $detail->is_owner_approval = OrderDetail::IS_OWNER_APPROVAL;
                    $detail->approval_uid = Yii::$app->user->id;
                    $detail->approval_date = date('Y-m-d H:i:s');
                    $detail->update();
                    //lock stock total
                    $stockTotal = StockTotal::find()->where(['material_id'=>$detail->material_id,'storeroom_id'=>$detail->storeroom_id])->one();
                    $stockTotal->lock_num = $detail->quantity;
                    $stockTotal->update();
                }
            }
            $approval->status = Approval::STATUS_IS_PASS;
            $approval->modified = date('Y-m-d H:i:s');
            $approval->update();
            foreach($details as $detail){
                if($detail->is_owner_approval == OrderDetail::IS_OWNER_APPROVAL){
                    
                }else{
                    $flag ++;
                }
            }
            if($flag == 0){
                Order::updateAll(['owner_approval'=>Order::OWNER_PASS_APPROVAL],['id'=>$order_id]);
            }
            if($orderInfo->need_fee_approval == Order::ORDER_NOT_NEED_FEE_APPROVAL){
                if($orderInfo->owner_approval == Order::OWNER_PASS_APPROVAL && $orderInfo->can_formal == Orde::IS_FORMAL){
                    $orderInfo->status = Order::ORDER_STATUS_IS_APPROVALED;
                    $orderInfo->update();

                    //扣除库存
                    foreach($details as $detail){
                        $stock = new Stock;
                        $stock->material_id = $detail->material->id;
                        $stock->storeroom_id = $detail->storeroom_id;
                        $stock->owner_id = $detail->owner_id;
                        $stock->actual_quantity = 0 - $detail->quantity;
                        $stock->stock_time = date('Y-m-d H:i:s');
                        $stock->created = date('Y-m-d H:i:s');
                        $stock->increase = Stock::IS_NOT_INCREASE;
                        $stock->order_id = $detail->order_id;
                        $stock->save(false);

                        //lock stock total
                        $stockTotal = StockTotal::find()->where(['material_id'=>$detail->material_id,'storeroom_id'=>$detail->storeroom_id])->one();
                        $stockTotal->lock_num = $stockTotal->lock_num - $detail->quantity;
                        $stockTotal->total = $stockTotal->total - $detail->quantity;
                        $stockTotal->update();
                    }
                    $orderInfo->consume();
                }
            }else{
                if($orderInfo->owner_approval == Order::OWNER_PASS_APPROVAL && $orderInfo->fee_approval == Order::ORDER_PASS_FEE_APPROVAL && $orderInfo->can_formal == Orde::IS_FORMAL){
                    $orderInfo->status = Order::ORDER_STATUS_IS_APPROVALED;
                    $orderInfo->update();

                    //扣除库存
                    foreach($details as $detail){
                        $stock = new Stock;
                        $stock->material_id = $detail->material->id;
                        $stock->storeroom_id = $detail->storeroom_id;
                        $stock->owner_id = $detail->owner_id;
                        $stock->actual_quantity = 0 - $detail->quantity;
                        $stock->stock_time = date('Y-m-d H:i:s');
                        $stock->created = date('Y-m-d H:i:s');
                        $stock->increase = Stock::IS_NOT_INCREASE;
                        $stock->order_id = $detail->order_id;
                        $stock->save(false);

                        //lock stock total
                        $stockTotal = StockTotal::find()->where(['material_id'=>$detail->material_id,'storeroom_id'=>$detail->storeroom_id])->one();
                        $stockTotal->lock_num = $stockTotal->lock_num - $detail->quantity;
                        $stockTotal->total = $stockTotal->total - $detail->quantity;
                        $stockTotal->update();
                    }
                    $orderInfo->consume();
                }
            }
            
        }
    }
    //预算所属人审批预算
    public function actionApprovalfee(){
        if(Yii::$app->request->isPost){
            $order_id = Yii::$app->request->post('order_id');
            $order = Order::findOne($order_id);
            if($order->need_fee_approval == Order::ORDER_NEED_FEE_APPROVAL){
                $order->fee_approval = Order::ORDER_PASS_FEE_APPROVAL;
                $order->fee_approval_uid = Yii::$app->user->id;
                $order->update();
            }
            if($order->owner_approval == Order::OWNER_PASS_APPROVAL && $order->fee_approval == Order::ORDER_PASS_FEE_APPROVAL && $order->can_formal == Orde::IS_FORMAL){
                $order->status = Order::ORDER_STATUS_IS_APPROVALED;
                $order->update();

                $order->consume();
            }
        }
    }
    /**
     * 发送费用审批
     * @return [type] [description]
     */
    public function actionSendapprovalfee(){
        if(Yii::$app->request->isPost){
            $order_id = Yii::$app->request->post('order_id');
            $order = Order::findOne($order_id);
            if($order->need_fee_approval == Order::ORDER_NEED_FEE_APPROVAL){
                $owner = Owner::getBigOwnerByUid($order->created_uid);
                if($owner){
                    $approval = Approval::find()->where(['order_id'=>$order_id,'type'=>Approval::TYPE_IS_FEE])->one();
                    if(empty($approval)){
                        $model = new Approval;
                        $model->order_id = $order_id;
                        $model->owner_id = $owner->id;
                        $model->applicant = $order->created_uid;
                        $model->type = Approval::TYPE_IS_FEE;
                        $model->created = date('Y-m-d H:i:s');
                        $model->modified = date('Y-m-d H:i:s');
                        $model->save();
                    }
                }
                $order->status = Order::ORDER_STATUS_IS_NEED_APPROVAL;
                $order->update();
            }
        }
    }
    //发送审批
    public function actionSendapproval(){
        if(Yii::$app->request->isPost){
            $order_id = Yii::$app->request->post('order_id');
            $order = Order::findOne($order_id);
            $orderDetail = OrderDetail::find()->with('material')->where(['order_id'=>$order_id])->all();
            $flag = '';
            foreach($orderDetail as $detail){
                if(StockTotal::checkQuantity($detail->material_id,$detail->storeroom_id,$detail->quantity) == false){
                    $flag .= $detail->material->name." ";
                }
            }
            if($flag == ''){
                $order->status = Order::ORDER_STATUS_IS_NEED_APPROVAL;
                $order->update();
                foreach($orderDetail as $detail){
                    if($detail->created_uid != $detail->owner_id){
                        $approval_record = Approval::find()->where(['order_id'=>$order_id,'type'=>Approval::TYPE_IS_MATERIAL,'owner_id'=>$detail->owner_id])->one();
                        if(empty($approval_record)){
                            $model = new Approval;
                            $model->order_id = $order_id;
                            $model->owner_id = $detail->owner_id;
                            $model->applicant = $order->created_uid;
                            $model->type = Approval::TYPE_IS_MATERIAL;
                            $model->created = date('Y-m-d H:i:s');
                            $model->modified = date('Y-m-d H:i:s');
                            $model->save();
                        }
                    }
                }
            }else{
                echo '由于较长时间没有发送审批,您订单中包含的物料'.$flag."已经库存不足，请您重新下单";
            }

        }
    }
    /**
     * action for delete user address
     * @throws \yii\web\HttpException
     */
    public function actionDeleteaddress()
    {
        if (!Yii::$app->request->isPost) {
            throw new HttpException(404, 'The requested page does not exist.');
        }

        $id = Yii::$app->request->post('id');
        $address = Address::find()->where(['uid' => Yii::$app->user->id, 'id' => $id, 'status' => 0])->one();
        if (!empty($address)) {
            if (Address::deleteAll(['uid' => Yii::$app->user->id, 'id' => $id])) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }
    /**
     * 预订单列表
     * @return [type] [description]
     */
    public function actionPre(){
        $params = Yii::$app->request->getQueryParams();
        list($data,$pages,$count) = OrderSearch::getPreData(Yii::$app->request->getQueryParams());
        $sidebar_name = '预订单';
        return $this->render('list', [
             'results' => $data,
             'pages' => $pages,
             'count'=>$count,
             'params'=>Yii::$app->request->getQueryParams(),
             'sidebar_name'=>$sidebar_name,
        ]);
    }
    /**
     * 进行中订单列表
     * @return [type] [description]
     */
    public function actionDoing(){
        $params = Yii::$app->request->getQueryParams();
        list($data,$pages,$count) = OrderSearch::getDoingData(Yii::$app->request->getQueryParams());
        $sidebar_name = '进行中的订单';
        return $this->render('doing', [
             'results' => $data,
             'pages' => $pages,
             'count'=>$count,
             'params'=>Yii::$app->request->getQueryParams(),
             'sidebar_name'=>$sidebar_name,
        ]);
    }
    /**
     * 已完成订单列表
     * @return [type] [description]
     */
    public function actionDone(){
        $params = Yii::$app->request->getQueryParams();
        list($data,$pages,$count) = OrderSearch::getDoneData(Yii::$app->request->getQueryParams());
        $sidebar_name = '已完成的订单';
        return $this->render('done', [
             'results' => $data,
             'pages' => $pages,
             'count'=>$count,
             'params'=>Yii::$app->request->getQueryParams(),
             'sidebar_name'=>$sidebar_name,
        ]);
    }
    /**
     * 异常订单列表
     * @return [type] [description]
     */
    public function actionExcept(){
        $params = Yii::$app->request->getQueryParams();
        list($data,$pages,$count) = OrderSearch::getExcepetData(Yii::$app->request->getQueryParams());
        $sidebar_name = '异常订单';
        return $this->render('except', [
             'results' => $data,
             'pages' => $pages,
             'count'=>$count,
             'params'=>Yii::$app->request->getQueryParams(),
             'sidebar_name'=>$sidebar_name,
        ]);
    }
    /**
     * 待别人审核的订单
     * @return [type] [description]
     */
    public function actionNeedapproval(){
        $params = Yii::$app->request->getQueryParams();
        list($data,$pages,$count) = OrderSearch::getNeedapprovalData(Yii::$app->request->getQueryParams());
        $sidebar_name = '进行中的订单';
        return $this->render('needapproval', [
             'results' => $data,
             'pages' => $pages,
             'count'=>$count,
             'params'=>Yii::$app->request->getQueryParams(),
             'sidebar_name'=>$sidebar_name,
        ]);
    }
    /**
     * 需要我审核的订单
     * @return [type] [description]
     */
    public function actionApproval(){
        $params = Yii::$app->request->getQueryParams();
        list($data,$pages,$count) = Approval::getMyData(Yii::$app->request->getQueryParams());
        $sidebar_name = '待审批订单';
        return $this->render('approval', [
             'results' => $data,
             'pages' => $pages,
             'count'=>$count,
             'params'=>Yii::$app->request->getQueryParams(),
             'sidebar_name'=>$sidebar_name,
        ]);
    }
    /**
     * check shippment method is exist 
     * @return [type] [description]
     */
    public function actionCheckshipmethod(){
        $this->enableCsrfValidation = false;
        if(Yii::$app->request->isPost){
            $storeroom_id = Yii::$app->request->post('storeroom_id');
            $to_city = Yii::$app->request->post('to_city');
            $transport_type = Yii::$app->request->post('transport_type');
            $order_type = Yii::$app->request->post('order_type');
            $templete = ShippmentCost::find()->where(['storeroom_id'=>$storeroom_id,'transport_type'=>$transport_type,'to_type'=>$order_type,'to_city'=>$to_city])->one();
            if(!empty($templete)){
                echo 1;
            }else{
                echo 0;
            }
        }
    }
}