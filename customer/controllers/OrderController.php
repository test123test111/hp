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
use common\models\SendEmail;

class OrderController extends CustomerController {
    public $layout = false;
    public $enableCsrfValidation;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['list', 'buy','update','view','viewapproval','getgoods','import','success','city','address','addressdisplay','approvalmaterial','approvalfee','sendapprovalfee','sendapproval','deleteaddress','pre','doing','done','exportdone','except','needapproval','approval','checkshipmethod','disagreefee','disagreeapproval','cancel','report'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
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
        $order = Order::find()->with('details')->where(['id'=>$id])->one();
        list($ship_fee,$fenjian_fee) = Yii::$app->budget->reckon($order->id);
        return $this->render('view', [
            'order' => $order,
            'ship_fee'=>$ship_fee,
            'fenjian_fee'=>$fenjian_fee,
        ]);
    }
    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewapproval($id)
    {   
        $order = Order::find()->with('details')->where(['id'=>$id])->one();
        list($ship_fee,$fenjian_fee) = Yii::$app->budget->reckon($order->id);
        return $this->render('viewapproval', [
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
            $datas = [
                ['序号','下单人','计划日期','发货仓库','运输时效','到达日期','是否保险','保险金额','收件类型','用途','收件单位','收件人','省份','城市','区县','地址','电话','备注','物料编码','数量']
            ];
            foreach($datas as $key=>$data){
                if($key == 0){
                    continue;
                }else{
                    $ret[$data[0]]['created_uid'] = trim($data[1]);
                    $ret[$data[0]]['send_date'] = trim($data[2]) == "" ? date('Y-m-d') : trim($data[2]);
                    $ret[$data[0]]['storeroom_id'] = trim($data[3]);  
                    $ret[$data[0]]['transport_type'] = trim($data[4]);
                    $ret[$data[0]]['arrive_date'] = !empty($data[5]) ? $date[5] : 0;
                    $ret[$data[0]]['insurance'] = trim($data[6]);
                    $ret[$data[0]]['insurance_price'] = trim($data[7]);
                    if(trim($data[8]) == "" || trim($data[8]) > 1){
                        $ret[$data[0]]['to_type'] = 0;
                    }else{
                        $ret[$data[0]]['to_type'] = trim($data[8]);
                    }
                    
                    $ret[$data[0]]['purpose'] = trim($data[9]);
                    $ret[$data[0]]['to_company'] = trim($data[10]);
                    $ret[$data[0]]['recipients'] = trim($data[11]);
                    $ret[$data[0]]['to_province'] = trim($data[12]);
                    $ret[$data[0]]['to_city'] = trim($data[13]);
                    $ret[$data[0]]['to_district'] = trim($data[14]);
                    $ret[$data[0]]['contact'] = trim($data[15]);
                    $ret[$data[0]]['phone'] = trim($data[16]);
                    $ret[$data[0]]['info'] = trim($data[17]);
                    $ret[$data[0]]['goods'][trim($data[18])] = trim($data[19]);
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
                if(!isset($error['transport_error'])){
                    $error['transport_error'] = [];
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
            $transport_type = Order::checkTransportType($value['transport_type']);
            if(empty($transport_type)){
                $error['transport_error'][$value['transport_type']] = $value['transport_type'];
            }elseif(empty($storeroom)){
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
                    if(($stock_total->total - $stock_total->lock_num) < $v1){
                        $error['total_error'][$k] = $k;
                    }
                }
            }
            //create address 
            if(!empty($owner)){
                $address = Address::find()->where(['uid'=>$owner->id,'company'=>$value['to_company']])->one();
                if(empty($address)){
                    $model = new Address;
                    $model->uid = $owner->id;
                    $model->name = $value['recipients'];
                    $model->phone = $value['phone'];
                    $model->province = $value['to_province'];
                    $model->city = $value['to_city'];
                    $model->area = $value['to_district'];
                    $model->address = $value['contact'];
                    $modle->save(false);
                }
            }
        }
        return $error;
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
                $model->send_date = $value['send_date'];
                $model->storeroom_id = $storeroom->id;
                $model->transport_type = Order::getMyTransportType($value['transport_type']);
                $model->arrive_date = $value['arrive_date'];
                $model->insurance = $value['insurance'];
                $model->insurance_price = $value['insurance_price'];
                $model->purpose = $value['purpose'];
                $model->info = $value['info'];
                $model->recipients = $value['recipients'];
                $model->storeroom_id = $storeroom->id;
                $model->owner_id = $owner->id;
                $model->to_city = $value['to_city'];
                $model->recipients = $value['recipients'];
                $model->to_province = $value['to_province'];
                $model->to_city = $value['to_city'];
                $model->to_district = $value['to_district'];
                $model->contact = $value['contact'];
                $model->phone = $value['phone'];
                $model->status = Order::ORDER_STATUS_IS_APPROVALED;
                $model->to_company = $value['to_company'];
                $model->owner_approval = Order::PASS_OWNER_APPROVAL;
                $model->detachBehavior('attributeStamp');
                $model->created_uid = $value['created_uid'];
                $model->type = Order::ORDER_TYPE_BATCH;
                $model->save(false);

                $model->viewid = date('Ymd')."-".$model->id;
                $model->update();

                foreach($value['goods'] as $key=>$v){
                    $material = Material::find()->where(['code'=>$key])->one();
                    $detail = new OrderDetail;
                    $detail->order_id = $model->id;
                    $detail->material_id = $material->id;
                    $detail->storeroom_id = $this->storeroom_id;
                    $detail->owner_id = $material->owner_id;
                    $detail->quantity = $v;
                    $detail->is_owner_approval = OrderDetail::IS_OWNER_APPROVAL;
                    $detail->approval_uid = $material->owner_id;
                    $detail->approval_date = time();
                    $detail->save();

                    $stock = new Stock;
                    $stock->material_id = $material->id;
                    $stock->storeroom_id = $detail->storeroom_id;
                    $stock->owner_id = $detail->owner_id;
                    $stock->actual_quantity = 0 - $v;
                    $stock->stock_time = date('Y-m-d H:i:s');
                    $stock->created = date('Y-m-d H:i:s');
                    $stock->increase = Stock::IS_NOT_INCREASE;
                    $stock->order_id = $detail->order_id;
                    $stock->save(false);

                    //subtract stock total
                    // StockTotal::updateTotal($model->storeroom_id,$material->id,(0 - $v));
                    $stockTotal = StockTotal::find()->where(['material_id'=>$material->id,"storeroom_id"=>$detail->storeroom_id])->one();
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
    public function actionSuccess(){
        $id = Yii::$app->request->get('id');
        $order = Order::find()->where(['viewid'=>$id])->one();
        return $this->render('success',['id'=>$id,'order'=>$order]);
    }
    public function actionReport(){
        $params = Yii::$app->request->getQueryParams();
        list($data,$pages,$count) = OrderSearch::getDoneData(Yii::$app->request->getQueryParams());
        $sidebar_name = '订单报告';
        return $this->render('report', [
             'results' => $data,
             'pages' => $pages,
             'count'=>$count,
             'params'=>Yii::$app->request->getQueryParams(),
             'sidebar_name'=>$sidebar_name,
             'menu_name'=>'report',
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
            $order_id = Yii::$app->request->post('id');
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
            $flag = 0;
            $approval = Approval::find()->where(['order_id'=>$order_id,'owner_id'=>Yii::$app->user->id,'type'=>Approval::TYPE_IS_MATERIAL])->one();
            $details = OrderDetail::find()->where(['order_id'=>$order_id,'owner_id'=>Yii::$app->user->id])->all();
            if(!empty($details)){
                foreach($details as $detail){
                    $detail->is_owner_approval = OrderDetail::IS_OWNER_APPROVAL;
                    $detail->approval_uid = Yii::$app->user->id;
                    $detail->approval_date = time();
                    $detail->update();

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
                if($orderInfo->can_formal == Order::IS_FORMAL){
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

                        if($stockTotal->total < $stockTotal->warning_quantity){
                            //您的物料（物料编号+物料名称）剩余库存为**，已达预警值，请您知悉，谢谢。
                            $ret = [
                                'code'=>$detail->material->code,
                                'name'=>$detail->material->name,
                                'email'=>$detail->owner->email,
                                'total'=>$stockTotal->total,
                                'type'=>'物料',
                            ];
                            $sendEmail = new SendEmail;
                            $sendEmail->template = 'stock';
                            $sendEmail->content = json_encode($ret);
                            $sendEmail->created = date('Y-m-d H:i:s');
                            $sendEmail->save();
                        }
                    }
                    $orderInfo->consume();
                }
            }else{
                if($orderInfo->owner_approval == Order::OWNER_PASS_APPROVAL && $orderInfo->fee_approval == Order::ORDER_PASS_FEE_APPROVAL && $orderInfo->can_formal == Order::IS_FORMAL){
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

                        if($stockTotal->total < $stockTotal->warning_quantity){
                            //您的物料（物料编号+物料名称）剩余库存为**，已达预警值，请您知悉，谢谢。
                            $ret = [
                                'code'=>$detail->material->code,
                                'name'=>$detail->material->name,
                                'email'=>$detail->owner->email,
                                'total'=>$stockTotal->total,
                                'type'=>'物料',
                            ];
                            $sendEmail = new SendEmail;
                            $sendEmail->template = 'stock';
                            $sendEmail->content = json_encode($ret);
                            $sendEmail->created = date('Y-m-d H:i:s');
                            $sendEmail->save();
                        }
                    }
                    $orderInfo->consume();
                }
            }
            echo 0;
            Yii::$app->end();
        }
    }
    //预算所属人审批预算
    public function actionApprovalfee(){
        if(Yii::$app->request->isPost){
            $order_id = Yii::$app->request->post('id');
            $order = Order::findOne($order_id);
            if($order->need_fee_approval == Order::ORDER_NEED_FEE_APPROVAL){
                $order->fee_approval = Order::ORDER_PASS_FEE_APPROVAL;
                $order->fee_approval_uid = Yii::$app->user->id;
                $order->update();
            }
            if($order->owner_approval == Order::OWNER_PASS_APPROVAL && $order->fee_approval == Order::ORDER_PASS_FEE_APPROVAL && $order->can_formal == Order::IS_FORMAL){
                $order->status = Order::ORDER_STATUS_IS_APPROVALED;
                $order->update();

                foreach($order->details as $detail){
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

                    if($stockTotal->total < $stockTotal->warning_quantity){
                        //您的物料（物料编号+物料名称）剩余库存为**，已达预警值，请您知悉，谢谢。
                        $ret = [
                            'code'=>$detail->material->code,
                            'name'=>$detail->material->name,
                            'email'=>$detail->owner->email,
                            'type'=>'物料',
                        ];
                        $sendEmail = new SendEmail;
                        $sendEmail->template = 'stock';
                        $sendEmail->content = json_encode($ret);
                        $sendEmail->created = date('Y-m-d H:i:s');
                        $sendEmail->save();
                    }

                }
                $order->consume();
            }
            echo 0;
        }
    }
    /**
     * 发送费用审批
     * @return [type] [description]
     */
    public function actionSendapprovalfee(){
        if(Yii::$app->request->isPost){
            $order_id = Yii::$app->request->post('id');
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
                if($order->status != Order::ORDER_STATUS_IS_NEED_APPROVAL){
                    $order->status = Order::ORDER_STATUS_IS_NEED_APPROVAL;
                    $order->update();
                }
                echo 1;
            }else{
                $str = '该订单不需要费用审批';
                echo json_encode($str);
            }
            
        }
    }
    //发送审批
    public function actionSendapproval(){
        if(Yii::$app->request->isPost){
            $order_id = Yii::$app->request->post('id');
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
                    if($order->created_uid != $detail->owner_id){
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
                echo 1;
            }else{
                $str = '由于较长时间没有发送审批,您订单中包含的物料'.$flag."已经库存不足，请您重新下单";
                echo json_encode($str);
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
             'menu_name'=>'order',
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
        return $this->render('list', [
             'results' => $data,
             'pages' => $pages,
             'count'=>$count,
             'params'=>Yii::$app->request->getQueryParams(),
             'sidebar_name'=>$sidebar_name,
             'menu_name'=>'order',
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
        return $this->render('list', [
             'results' => $data,
             'pages' => $pages,
             'count'=>$count,
             'params'=>Yii::$app->request->getQueryParams(),
             'sidebar_name'=>$sidebar_name,
             'menu_name'=>'order',
        ]);
    }
    /**
     * 已完成订单列表
     * @return [type] [description]
     */
    public function actionExportdone(){
        $result = OrderSearch::getExportDoneData(Yii::$app->request->getQueryParams());
        $filename = '订单报表.csv';
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        print(chr(0xEF).chr(0xBB).chr(0xBF));
        echo $result;
    }
    /**
     * 异常订单列表
     * @return [type] [description]
     */
    public function actionExcept(){
        $params = Yii::$app->request->getQueryParams();
        list($data,$pages,$count) = OrderSearch::getExcepetData(Yii::$app->request->getQueryParams());
        $sidebar_name = '异常订单';
        return $this->render('list', [
             'results' => $data,
             'pages' => $pages,
             'count'=>$count,
             'params'=>Yii::$app->request->getQueryParams(),
             'sidebar_name'=>$sidebar_name,
             'menu_name'=>'order',
        ]);
    }
    /**
     * 待别人审核的订单
     * @return [type] [description]
     */
    public function actionNeedapproval(){
        $params = Yii::$app->request->getQueryParams();
        list($data,$pages,$count) = OrderSearch::getNeedapprovalData(Yii::$app->request->getQueryParams());
        $sidebar_name = '待审批订单';
        return $this->render('list', [
             'results' => $data,
             'pages' => $pages,
             'count'=>$count,
             'params'=>Yii::$app->request->getQueryParams(),
             'sidebar_name'=>$sidebar_name,
             'menu_name'=>'order',
        ]);
    }
    /**
     * 需要我审核的订单
     * @return [type] [description]
     */
    public function actionApproval(){
        $params = Yii::$app->request->getQueryParams();
        list($data,$pages,$count) = Approval::getMyData(Yii::$app->request->getQueryParams());
        $sidebar_name = '审批订单';
        return $this->render('approval', [
             'results' => $data,
             'pages' => $pages,
             'count'=>$count,
             'params'=>Yii::$app->request->getQueryParams(),
             'sidebar_name'=>$sidebar_name,
             'menu_name'=>'order',
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
    /**
     * [actionDisagreeapproval description]
     * @return [type] [description]
     */
    public function actionDisagreefee(){
        if(Yii::$app->request->isPost){
            $order_id = Yii::$app->request->post('id');
            $order = Order::findOne($order_id);
            if(!empty($order)){
                if($order->need_fee_approval == Order::ORDER_NEED_FEE_APPROVAL){
                    $order->status = Order::ORDER_STATUS_IS_APPROVAL_FAIL;
                    $order->fee_approval = Order::ORDER_REJECT_FEE_APPROVAL;
                    $order->fee_approval_uid = Yii::$app->user->id;
                    $order->update(false);

                    foreach($order->details as $detail){
                        //lock stock total
                        $stockTotal = StockTotal::find()->where(['material_id'=>$detail->material_id,'storeroom_id'=>$detail->storeroom_id])->one();
                        $stockTotal->lock_num = $stockTotal->lock_num - $detail->quantity;
                        $stockTotal->total = $stockTotal->total - $detail->quantity;
                        $stockTotal->update();
                    }
                }
            }
            echo 0;
        }
    }
    /**
     * [actionDisagreeapproval description]
     * @return [type] [description]
     */
    public function actionDisagreeapproval(){
        if(Yii::$app->request->isPost){
            $order_id = Yii::$app->request->post('id');
            $order = Order::findOne($order_id);
            if(!empty($order)){
                $order->status = Order::ORDER_STATUS_IS_APPROVAL_FAIL;
                $order->update(false);

                foreach($order->details as $detail){
                    //只解锁未被拒绝的订单
                    if($detail->is_owner_approval != OrderDetail::IS_REJECT_OWNER_APPROVAL){
                        //lock stock total
                        $stockTotal = StockTotal::find()->where(['material_id'=>$detail->material_id,'storeroom_id'=>$detail->storeroom_id])->one();
                        $stockTotal->lock_num = $stockTotal->lock_num - $detail->quantity;
                        $stockTotal->total = $stockTotal->total - $detail->quantity;
                        $stockTotal->update();

                        $detail->is_owner_approval = OrderDetail::IS_REJECT_OWNER_APPROVAL;
                        $detail->update(false);

                    }
                    
                }
            }
            echo 0;
        }
    }
    /**
     * action for cancel order
     * @return [type] [description]
     */
    public function actionCancel(){
        if(Yii::$app->request->isPost){
            $order_id = Yii::$app->request->post('id');
            $order = Order::findOne($order_id);
            if(empty($order) || $order->status != Order::ORDER_STATUS_IS_PRE){
                echo 1;
                Yii::$app->end();
            }
            $approval = Approval::find()->where(['order_id'=>$order_id,'type'=>Approval::STATUS_IS_PASS])->one();
            if(!empty($approval)){
                echo 2;
                Yii::$app->end();
            }
            Approval::deleteAll(['order_id'=>$order_id]);
            foreach($order->details as $value){
                $stockTotal = StockTotal::find()->where(['material_id'=>$value->material_id,'storeroom_id'=>$value->storeroom_id])->one();
                $num = 0 - $value->quantity;
                $stockTotal->updateCounters(['lock_num' => $num]);
            }
            $order->is_del = Order::ORDER_IS_DEL;
            $order->status = Order::ORDER_STATUS_IS_CANCEL;
            if($order->update(false)){
                echo 0;
            }
        }
    }
}