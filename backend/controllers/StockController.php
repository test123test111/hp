<?php

namespace backend\controllers;

use Yii;
use backend\models\Stock;
use backend\models\Owner;
use backend\models\Storeroom;
use backend\models\Material;
use backend\models\Share;
use backend\models\StockTotal;
use backend\models\search\StockSearch;
use backend\components\BackendController;
use backend\models\Upload;
use common\models\SendEmail;
class StockController extends BackendController {
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
        $searchModel = new StockSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    /**
     * Displays the create page
     */
    public function actionCreate() {
        $model = new Stock;
        // collect user input data
        if (isset($_POST['Stock'])) {
            $model->load($_POST);
            if($_POST['Stock']['warning_quantity'] == ""){
                $model->warning_quantity = 0;
            }else{
                $model->warning_quantity = $_POST['Stock']['warning_quantity'];
            }
            if ($model->validate()) {
                $db = Stock::getDb();
                $transaction = $db->beginTransaction();
                try{
                    $material = Material::findOne($model->material_id);
                    $model->owner_id = $material->owner_id;
                    $model->save();
                    //create a data in stock total
                    StockTotal::updateTotal($model->storeroom_id,$model->material_id,$model->actual_quantity,$model->warning_quantity,$model->owner_id);

                    Share::updateShare($model->owner_id,$model->owner_id,$model->material_id,$model->storeroom_id);
                    $transaction->commit();
                    //create send mail log

                    Yii::$app->session->setFlash('success', '新建成功！');
                    // $countshare = Share::find()->where(['owner_id'=>$material->owner_id,'status'=>Share::STATUS_IS_NORMAL])->count();
                    // if($countshare > 1){
                    //     $share = "已分享";
                    // }else{
                    //     $share = "未分享";
                    // }
                    // $ret = [
                    //     'code'=>$material->code,
                    //     'name'=>$material->name,
                    //     'stock_time'=>$model->stock_time,
                    //     'quantity'=>$model->actual_quantity,
                    //     'property'=>$material->getMyPropertyName(),
                    //     'share'=>$share,
                    //     'delivery'=>$model->delivery,
                    //     'email'=>$material->owners->email,
                    //     'info'=>"",
                    // ];
                    // $sendEmail = new SendEmail;
                    // $sendEmail->template = 'newstock';
                    // $sendEmail->content = json_encode($ret);
                    // $sendEmail->created = date('Y-m-d H:i:s');
                    // $sendEmail->save();
                    $this->redirect("/stocktotal/list");
                }catch (\Exception $e) {
                   $transaction->rollback();
                   throw new \Exception($e->getMessage(), $e->getCode());
                }
            }
        }
        return $this->render('create', array(
            'model' => $model,'isNew'=>true,
        )); 
    }
    public function actionExport(){
        if(isset($_GET['mid']) && $_GET['mid'] != 0){
            $material_id = $_GET['mid'];
            $storeroom_id = $_GET['sid'];
            $increase = $_GET['increase'];
            $filename = Material::findOne($material_id)->name;
            //chu ku report
            if($increase == Stock::IS_NOT_INCREASE){
                $filename .= "出库报表.xls";
                $stocks = Stock::find()->where(['material_id'=>$material_id,'storeroom_id'=>$storeroom_id,'increase'=>$increase,'destory_reason'=>""])->orderby(['material_id'=>SORT_DESC,'id'=>SORT_DESC])->all();
                $objPHPExcel = new \PHPExcel();
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1','物料')
                            ->setCellValue('B1','仓库')
                            ->setCellValue('C1','所属人')
                            ->setCellValue('D1','活动名称')
                            ->setCellValue('E1','出库数量')
                            ->setCellValue('F1','出库时间')
                            ->setCellValue('G1','订单号');
                $i=2;
                foreach($stocks as $v)
                {
                    $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$i, $v->material->name)
                                ->setCellValue('B'.$i, $v->storeroom->name)
                                ->setCellValue('C'.$i, $v->owners->english_name)
                                ->setCellValue('D'.$i, $v->active)
                                ->setCellValue('E'.$i, ( 0 - $v->forecast_quantity))
                                ->setCellValue('F'.$i, $v->actual_quantity)
                                ->setCellValue('G'.$i, $v->stock_time)
                                ->setCellValue('H'.$i, $v->orders->viewid);
                                $i++;
                }
                $objPHPExcel->setActiveSheetIndex(0);
            }else{
                $filename .= "入库报表.xls";
                $stocks = Stock::find()->where(['material_id'=>$material_id,'storeroom_id'=>$storeroom_id,'increase'=>$increase])->orderby(['material_id'=>SORT_DESC,'id'=>SORT_DESC])->all();
                $objPHPExcel = new \PHPExcel();
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1','物料')
                            ->setCellValue('B1','仓库')
                            ->setCellValue('C1','所属人')
                            ->setCellValue('D1','活动名称')
                            ->setCellValue('E1','预计入库数量')
                            ->setCellValue('F1','实际入库数量')
                            ->setCellValue('G1','入库时间')
                            ->setCellValue('H1','送货方');
                $i=2;
                foreach($stocks as $v)
                {
                    $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$i, $v->material->name)
                                ->setCellValue('B'.$i, $v->storeroom->name)
                                ->setCellValue('C'.$i, $v->owners->english_name)
                                ->setCellValue('D'.$i, $v->active)
                                ->setCellValue('E'.$i, $v->forecast_quantity)
                                ->setCellValue('F'.$i, $v->actual_quantity)
                                ->setCellValue('G'.$i, $v->stock_time)
                                ->setCellValue('H'.$i, $v->delivery);
                                $i++;
                }
                $objPHPExcel->setActiveSheetIndex(0);
            }
            header('Content-Type: application/vnd.ms-excel;charset=utf-8');
            header('Content-Disposition: attachment;filename='.$filename);
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        }
    }
    public function actionExportstock(){
        if(isset($_GET['sid']) && $_GET['sid'] != 0){
            $storeroom_id = $_GET['sid'];
            //chu ku report
            //$ret['store'] = $result->storeroom->name;
            $filename = "库存报表.xls";
            $data = StockTotal::getExportDataByStoreroomId($_GET['sid']);
            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1','仓库')
                        ->setCellValue('B1','物料')
                        ->setCellValue('C1','所属人')
                        ->setCellValue('D1','现有库存')
                        ->setCellValue('E1','最后一次入库时间')
                        ->setCellValue('F1','最后一次出库时间')
                        ->setCellValue('G1','备注');
            $i=2;
            foreach($data as $v)
            {
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$i, $v['store'])
                            ->setCellValue('B'.$i, $v['material'])
                            ->setCellValue('C'.$i, $v['owner'])
                            ->setCellValue('D'.$i, $v['total'])
                            ->setCellValue('E'.$i, $v['last_income'])
                            ->setCellValue('F'.$i, $v['last_output'])
                            ->setCellValue('G'.$i, $v['info']);
                            $i++;
            }
            $objPHPExcel->setActiveSheetIndex(0);
            
            header('Content-Type: application/vnd.ms-excel;charset=utf-8');
            header('Content-Disposition: attachment;filename='.$filename);
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        }
    }
    /**
     * Displays the create page
     */
    public function actionUpdate($id) {
        $model = new Stock;
        $id = $_GET['id'];
        if($id){
            $model = $this->loadModel($id);
            //update StockTotal
            if (!empty($_POST)) {
                //update StockTotal
                if($model->actual_quantity != $_POST['Stock']['actual_quantity']){
                    if($model->actual_quantity > $_POST['Stock']['actual_quantity']){
                        StockTotal::updateTotal($model->storeroom_id,$model->material_id,($_POST['Stock']['actual_quantity'] - $model->actual_quantity));
                    }else{
                        StockTotal::updateTotal($model->storeroom_id,$model->material_id,($model->actual_quantity - $_POST['Stock']['actual_quantity']));
                    }
                }
                if ($model->load($_POST) && $model->save()) {
                    Yii::$app->session->setFlash('success', '修改成功!');
                    return $this->redirect(Yii::$app->request->getReferrer());
                }
            }
        }
        return $this->render('create',['model'=>$model,'isNew'=>false]);
    }
    public function actionSearch(){
        $model = new Stock(['scenario'=>'search']);
        $dataProvider = [];
        if(isset($_POST['Stock'])){
            $model->load($_POST);
            if($model->validate()){
                $searchModel = new StockSearch;
                $dataProvider = $searchModel->searchByPost($_POST['Stock']['material_id'],$_POST['Stock']['storeroom_id'],$_POST['Stock']['increase']);
            }
        }
        return $this->render("search",['model'=>$model,'dataProvider'=>$dataProvider]);
    }
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Stock::findOne($id);
        if ($model === null) throw new CHttpException(404, 'The requested page does not exist.');
        
        return $model;
    }
    public function actionDestory(){
        $model = new Stock(['scenario'=>'destory']);
        if (isset($_POST['Stock'])) {
            $model->load($_POST);
            if ($model->validate()) {
                $stock = Stock::find()->where(['material_id'=>$_POST['Stock']['material_id']])->orderby(['id'=>SORT_DESC])->one();
                $model->owner_id = $stock->owner_id;
                $model->increase = Stock::IS_NOT_INCREASE;
                $model->actual_quantity = 0 - $_POST['Stock']['actual_quantity'];
                $model->stock_time = date('Y-m-d H:i:s');
                $model->save();
                //create a data in stock total
                StockTotal::updateTotal($model->storeroom_id,$model->material_id,$model->actual_quantity);
                Yii::$app->session->setFlash('success', '成功销毁物料，库存已经相应减少');
                // $this->redirect("/stock/list");
            }
        }
        return $this->render('destory',['model'=>$model]);
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
    public function actionOwner(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $material_id = $parents[0];
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
                $out = Owner::getOwnerByMid($material_id);
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
    public function actionStoreroom(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $owner_id = $parents[0];
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
                $out = Storeroom::getStoreroomByOid($owner_id);
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
}
