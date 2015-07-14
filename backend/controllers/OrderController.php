<?php

namespace backend\controllers;

use Yii;
use backend\models\Order;
use backend\models\City;
use backend\models\OrderSign;
use backend\models\OrderPackage;
use backend\models\OrderDetail;
use backend\models\Package;
use backend\models\Upload;
use backend\models\search\OrderSearch;
use backend\models\search\OrderStockSearch;
use backend\components\BackendController;
use common\models\ShippmentCost;
class OrderController extends BackendController {
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
        $searchModel = new OrderSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),Yii::$app->user->identity->storeroom_id);

        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'status'=>$_GET['OrderSearch']['status'],
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
            $dataProvider = $searchModel->searchByPost($_POST['orderid'],Yii::$app->user->identity->storeroom_id);
        }
        return $this->render("search",['model'=>$model,'dataProvider'=>$dataProvider]);
    }
    /**
     * Displays the create page
     */
    public function actionCreate() {
        $model = new Order;
        // collect user input data
        if (isset($_POST['Order'])) {
            $model->load($_POST);
                $results = $model->getCanUseGoodsByOwnerId();
                return $this->render('create', [
                    'results' => $results,
                    'model'=>$model,
                    'ischange'=>true,
                    'owner_id'=>$_POST['Order']['owner_id'],
                    'storeroom_id'=>$_POST['Order']['storeroom_id'],
                ]);
        }
        return $this->render('create', array(
            'model' => $model,'isNew'=>true,'ischange'=>false,
        )); 
    }
    /**
     * [actionConfirm description]
     * @return [type] [description]
     */
    public function actionConfirm(){
        $ordersIds = $_POST['selection'];
        $orders = Order::find()->where(['id'=>$ordersIds,'is_del'=>Order::ORDER_IS_NOT_DEL])->all();
        foreach($orders as $order){
            if($order->status != Order::NEW_ORDER){
                continue;
            }else{
                $order->status = Order::CONFIRM_ORDER;
                $order->save(false);
            }
        }
        return $this->redirect('/order/list?OrderSearch[status]=4');
    }
    /**
     * [actionShipping description]
     * @return [type] [description]
     */
    public function actionShipping(){
        $ordersIds = $_POST['selection'];
        $orders = Order::find()->where(['id'=>$ordersIds,'is_del'=>Order::ORDER_IS_NOT_DEL])->all();
        foreach($orders as $order){
            if($order->status != Order::PACKAGE_ORDER){
                continue;
            }else{
                $order->status = Order::SHIPPING_ORDER;
                $order->save(false);
            }
        }
        return $this->redirect('/order/list?OrderSearch[status]=2');
    }
    /**
     * [actionSign description]
     * @return [type] [description]
     */
    public function actionSign(){
        $ordersIds = $_POST['selection'];
        $orders = Order::find()->where(['id'=>$ordersIds,'is_del'=>Order::ORDER_IS_NOT_DEL])->all();
        foreach($orders as $order){
            if($order->status != Order::SHIPPING_ORDER){
                continue;
            }else{
                $order->status = Order::SIGN_ORDER;
                $order->save(false);
            }
        }
        return $this->redirect('/order/list?OrderSearch[status]=3');
    }
    /**
     * Displays the create page
     */

    public function actionCheck() {
        $model = new Order;
        // collect user input data
        if(isset($_POST['confirm_end'])){
            $model->load($_POST);
            if ($model->validate()) {
                $db = Order::getDb();
                $transaction = $db->beginTransaction();
                try{
                    $model->to_province = City::findOne($_POST['Order']['to_province'])->name;
                    $model->to_city = City::findOne($_POST['Order']['to_city'])->name;
                    $model->source = Order::ORDER_SOURCE_CUSTOMER;
                    $model->save();
                    $model->viewid = date('Ymd')."-".$model->id;
                    $model->update();
                    //create order detail 
                    $model->createOrderDetail($_POST['OrderDetail']);
                    $transaction->commit();
                    $this->redirect("/order/list?OrderSearch[status]=0");
                }catch (\Exception $e) {
                    $transaction->rollback();
                    throw new \Exception($e->getMessage(), $e->getCode());
                }
                
            }
        }
        if (isset($_POST['selection'])) {
            foreach($_POST['selection'] as $key=>$value){
                if($value['count'] == 0){
                    unset($_POST['selection'][$key]);
                }
            }
            return $this->render('checkaddress', array(
                'model' => $model,'isNew'=>true,'data'=>$_POST['selection'],'owner_id'=>$_POST['Order']['owner_id'],
                    'storeroom_id'=>$_POST['Order']['storeroom_id'],
            )); 
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
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $order = Order::find()->where(['id'=>$id,'is_del'=>Order::ORDER_IS_NOT_DEL])->one();
        if(Yii::$app->user->identity->storeroom_id != Order::BIGEST_STOREROOM_ID){
            if($order->storeroom_id != Yii::$app->user->identity->storeroom_id){
                throw new \Exception("Error Processing Request", 404);
            }
        }
        $order_package = OrderPackage::find()->where(['order_id'=>$id])->one();
        $detail = OrderDetail::find()->where(['order_id'=>$id])->all();
        $sign = OrderSign::findOne($id);
        // $package = [];
        // if(!empty($order_package)){
            $package = Package::find()->where(['order_view_id'=>$order->viewid])->one();
        // }
        return $this->render('view', [
            'order' => $order,
            'package' => $package,
            'detail' =>$detail,
            'sign' => $sign,
        ]);
    }
    public function actionChange(){
        if(isset($_POST['orderid'])){
            $order = Order::findOne($_POST['orderid']);
            if(!empty($order)){
                $order->status = $_POST['status'];
                $order->save(false);

                //create queue to send email
                if($order->status == Order::SIGN_ORDER){
                    //Yii::$app->gqueue->createJob('send_email','gcommon\components\gqueue\workers\SendEmail',["type"=>Order::SIGN_ORDER,'id'=>$order->id]);
                }
                // if($_POST['status'] == Order::CONFIRM_ORDER){
                //     //send mail to customer
                //     Yii::$app->mail->compose('confirm',['order'=>$order])
                //          ->setFrom('liuwanglei2001@163.com')
                //          ->setTo('liuwanglei@goumin.com')
                //          ->setSubject("订单确认通知")
                //          ->send();
                // }
                // if($_POST['status'] == Order::SIGN_ORDER){
                //     Yii::$app->mail->compose('sign',['order'=>$order])
                //          ->setFrom('liuwanglei2001@163.com')
                //          ->setTo('liuwanglei@goumin.com')
                //          ->setSubject("订单签收通知")
                //          ->send();
                // }
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
     * [actionPrint description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionMarksign($id){
        $order = $this->loadModel($id);
        if($order->status != Order::ORDER_STATUS_IS_TRUCK && $order->status != Order::ORDER_STATUS_IS_UNSIGN){
            throw new CHttpException(404, '数据错误，请检查一下订单是否是发货状态，不是发货状态的订单不能标记为签收');
        }
        $model = new OrderSign;
        $model->order_viewid = $order->viewid;
        if(Yii::$app->request->isPost){
            $model->load($_POST);
            $model->sign_date = $_POST['sign_date-ordersign-sign_date'];
            if($model->validate()){
                if($model->save()){
                    $fee = 0;
                    if(isset($order->package)){
                        if($order->package->throw_weight > $order->package->actual_weight){
                            $weight = $order->package->throw_weight;
                        }else{
                            $weight = $order->package->actual_weight;
                        }
                        list($result,$fee,$return_code,$tariff) = ShippmentCost::getFeeByProperty($order->storeroom_id,$order->to_district,$weight,$order->transport_type,$order->to_type);
                    }
                    $order->real_ship_fee = $fee;
                    $order->status = Order::ORDER_STATUS_IS_SIGN;
                    $order->save(false);
                    //扣除真正用户预算
                    $order->redecareConsume();
                    //create queue to send email
                    //Yii::$app->gqueue->createJob('send_email','gcommon\components\gqueue\workers\SendEmail',["type"=>Order::SIGN_ORDER,'id'=>$order->id]);
                }
                return $this->redirect("/order/list?OrderSearch[status]=5");
            }
        }
        return $this->render('marksign',['id'=>$id,'order'=>$order,'model'=>$model]);
    }
    public function actionMarkunsign($id){
        $order = $this->loadModel($id);
        if($order->status != Order::ORDER_STATUS_IS_TRUCK){
            throw new CHttpException(404, '数据错误，请检查一下订单是否是发货状态，不是发货状态的订单不能标记为签收');
        }
        if(Yii::$app->user->identity->storeroom_id != Order::BIGEST_STOREROOM_ID){
            if($order->storeroom_id != Yii::$app->user->identity->storeroom_id){
                throw new \Exception("Error Processing Request", 404);
            }
        }
        $model = new OrderSign;
        $model->order_viewid = $order->viewid;
        if(Yii::$app->request->isPost){
            $model->load($_POST);
            $model->sign_date = $_POST['sign_date-ordersign-sign_date'];
            if($model->validate()){
                $model->type = OrderSign::ORDER_IS_NOT_SIGNED;
                if($model->save()){
                    $order->status = Order::ORDER_STATUS_IS_UNSIGN;
                    $order->save(false);
                }
                return $this->redirect("/order/list?OrderSearch[status]=6");
            }
        }
        return $this->render('markunsign',['id'=>$id,'order'=>$order,'model'=>$model]);
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
    public function actionUploadfile(){
        $this->enableCsrfValidation = false;
        $num = $_POST['num'];
        // print_r($_FILES);exit;
        if($_FILES){
            $model = new Upload;
            $result = $model->uploadImage($_FILES,false,"picture");
            if($result[0] == true){
                    echo <<<EOF
            <script>parent.stopSend("{$num}","{$result[1]}");</script>
EOF;
            }else{
                echo <<<EOF
            <script>alert("{$result[1]}");</script>
EOF;
            }
        }
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
    public function actionProductline(){
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
    public function actionImport(){
        $model = new Order;
        $error = [];
        $right = false;
        if(isset($_POST['Order']) && $_POST['Order'] != ""){
            $objPHPExcel = new \PHPExcel();
            // $objPHPExcel = \PHPExcel_IOFactory::load($_FILES["Order"]["tmp_name"]['file']);
            // $datas = $objPHPExcel->getSheet(0)->toArray();
            // $ret = [];
            // $datas = [
            //     ['序号','收件人','收件地址','收件人电话','收件城市','活动','发货仓库','物料编码','物料属主','数量','到货需求','备注'],
            //     ['1','','','','','','北京中央库','JIHFSN899011','alisa','20','4小时','must be'],
            //     ['1','wanglei','beijing office','13800138000','beijing','2014 word cup','北京中央库','GSDGSG99990SGA','alisa','5','4小时','must be'],
            //     ['2','lisi','beijing office','13800138000','beijing','2014 word cup','北京中央库','JIHFSN899011','alisa','40','4小时','must be'],
            // ];
            $datas = [
                ['序号','订单类型','下单人','计划日期','发货仓库','运输时效','到达日期','是否保险','保险金额','收件类型','用途','收件单位','收件人','省份','城市','区县','地址','电话','备注','物料编码','数量'],
                [1,1,'jnjn.km','2015-06-18','中央库','24小时','2015-06-19','0','0','0','暑假专用','北京市宠物乐园文化公司','王二麻子','北京市','北京市','北京市辖区','北京市南三环东路','13810595355','包装好','SM-20150617-8',10],
                [1,1,'jnjn.km','2015-06-18','中央库','24小时','2015-06-19','0','0','0','暑假专用','北京市宠物乐园文化公司','王二麻子','北京市','北京市','北京市辖区','北京市南三环东路','13810595355','包装好','SM-20150617-8',1000000],
                [1,1,'jnjn.km','2015-06-18','中央库','24小时','2015-06-19','0','0','0','暑假专用','北京市宠物乐园文化公司','王二麻子','北京市','北京市','北京市辖区','北京市南三环东路','13810595355','包装好','SM-20150617-2131238',1000000],
            ];
            foreach($datas as $key=>$data){
                if($key == 0){
                    continue;
                }else{
                    $ret[$data[0]]['type'] = trim($data[1]) > 1 ? 0 : $data[1];
                    $ret[$data[0]]['owner_id'] = trim($data[2]);
                    $ret[$data[0]]['send_date'] = trim($data[3]) == "" ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s',strtotime(trim($data[2])));
                    $ret[$data[0]]['storeroom_id'] = trim($data[4]);  
                    $ret[$data[0]]['transport_type'] = trim($data[5]);
                    $ret[$data[0]]['arrive_date'] = !empty($data[6]) ? $data[6] : 0;
                    $ret[$data[0]]['insurance'] = trim($data[7]) > 1 ? 0 : $data[7];
                    $ret[$data[0]]['insurance_price'] = trim($data[8]);
                    if(trim($data[9]) == "" || trim($data[9]) > 1){
                        $ret[$data[0]]['to_type'] = 0;
                    }else{
                        $ret[$data[0]]['to_type'] = trim($data[9]);
                    }
                    
                    $ret[$data[0]]['purpose'] = trim($data[10]);
                    $ret[$data[0]]['to_company'] = trim($data[11]);
                    $ret[$data[0]]['recipients'] = trim($data[12]);
                    $ret[$data[0]]['to_province'] = trim($data[13]);
                    $ret[$data[0]]['to_city'] = trim($data[14]);
                    $ret[$data[0]]['to_district'] = trim($data[15]);
                    $ret[$data[0]]['contact'] = trim($data[16]);
                    $ret[$data[0]]['phone'] = trim($data[17]);
                    $ret[$data[0]]['info'] = trim($data[18]);
                    $ret[$data[0]]['goods'][trim($data[19])] = trim($data[20]);
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
                if(!isset($error['transport_cost'])){
                    $error['transport_cost'] = [];
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
            $oModel = new \backend\models\Order;
            $transport_type = $oModel->checkTransportType($value['transport_type']);
            $templete = ShippmentCost::find()->where(['storeroom_id'=>$storeroom->id,'transport_type'=>$transport_type,'to_type'=>$value['to_type'],'to_city'=>$value['to_district']])->one();

            if(empty($transport_type)){
                $error['transport_error'][$value['to_district']] = $value['transport_type'];
            }elseif(empty($storeroom)){
                $error['storeroom_error'][$value['storeroom_id']] = $value['storeroom_id'];
            }elseif(empty($owner)){
                $error['owner_error'][$value['owner_id']] = $value['owner_id'];
            }elseif($templete == false){
                $error['transport_cost'][$value['to_district']] = $value['transport_type'];
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
                $model->transport_type = $model->checkTransportType($value['transport_type']);
                $model->arrive_date = $value['arrive_date'];
                $model->insurance = $value['insurance'];
                $model->insurance_price = $value['insurance_price'];
                $model->purpose = $value['purpose'];
                $model->info = $value['info'];
                $model->recipients = $value['recipients'];
                $model->storeroom_id = $storeroom->id;
                $model->created_uid = $owner->id;
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
                $model->type = $value['type'];
                $model->modified_uid = $owner->id;
                $model->hhg_uid = Yii::$app->user->id;
                $model->save(false);

                $model->viewid = date('Ymd')."-".$model->id;
                $model->update();
                foreach($value['goods'] as $key=>$v){
                    $material = Material::find()->where(['code'=>$key])->one();
                    $detail = new OrderDetail;
                    $detail->order_id = $model->id;
                    $detail->material_id = $material->id;
                    $detail->storeroom_id = $model->storeroom_id;
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
}
