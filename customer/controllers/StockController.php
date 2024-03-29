<?php

namespace customer\controllers;

use Yii;
use customer\models\Stock;
use backend\models\Material;
use backend\models\StockTotal;
use customer\models\search\StockSearch;
use customer\components\CustomerController;
use backend\models\Upload;
use customer\models\Storeroom;
use common\models\Share;

class StockController extends CustomerController {
    public $layout = false;
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
    // /**
    //  * Displays the page list
    //  */
    // public function actionList() {
    //     $searchModel = new StockSearch;
    //     $dataProvider = $searchModel->searchList(Yii::$app->request->getQueryParams());
    //     return $this->render('list', [
    //         'dataProvider' => $dataProvider,
    //         'searchModel' => $searchModel,
    //     ]);
    // }
    /**
     * Displays the page list
     */
    /**
     * Displays the page list
     */
    public function actionList() {
        list($data,$pages,$count) = Stock::getMyData(Yii::$app->request->getQueryParams());
        $owners = Share::find()->select('owner_id')->distinct('owner_id')->with('owners')->where(['to_customer_id'=>Yii::$app->user->id,'status'=>Share::STATUS_IS_NORMAL])->all();
        return $this->render('list', [
             'results' => $data,
             'pages' => $pages,
             'count'=>$count,
             'params'=>Yii::$app->request->getQueryParams(),
             'storerooms'=>Storeroom::find()->all(),
             'ownersData'=>$owners,
        ]);
    }
    /**
     * import stock total excel
     * @return [type] [description]
     */
    public function actionImport(){
        $result = Stock::getImportData(Yii::$app->request->getQueryParams());
        $filename = '库存报表.csv';
        $filename = iconv('utf-8', 'GB2312', $filename );
        header("Content-type:text/csv;charset=utf-8");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        print(chr(0xEF).chr(0xBB).chr(0xBF));
        echo $result;
    }
    /**
     * stock detail 
     * @return [type] [description]
     */
    public function actionDetail(){
        $params = Yii::$app->request->getQueryParams();
        if(!empty($params)){
            list($data,$pages,$count) = Stock::getDetail(Yii::$app->request->getQueryParams());
            return $this->render('detail', [
                 'results' => $data,
                 'pages' => $pages,
                 'count'=>$count,
                 'params'=>$params,
                 'storerooms'=>Storeroom::find()->all(),
            ]);
        }
        return $this->render('detail',['storerooms'=>Storeroom::find()->all(),'params'=>$params]);
    }
    /**
     * stock detail 
     * @return [type] [description]
     */
    public function actionExportdetail(){
        $result = Stock::getExportDetail(Yii::$app->request->getQueryParams());
        $filename = '出入库报表.csv';
        $filename = iconv('utf-8', 'GB2312', $filename );
        header("Content-type:text/csv;charset=utf-8");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        print(chr(0xEF).chr(0xBB).chr(0xBF));
        echo $result;
    }
    /**
     * Displays the page list
     */
    public function actionInout() {
        // list($data,$pages,$count) = Stock::getDetail(Yii::$app->request->getQueryParams());
        // return $this->render('detail', [
        //     'results' => $data,
        //      'pages' => $pages,
        //      'count'=>$count,
        //      'params'=>Yii::$app->request->getQueryParams(),
        // ]);
        return $this->render('inout');
    }
    /**
     * Displays the page list
     */
    public function actionOutput() {
        $searchModel = new StockSearch;
        $dataProvider = $searchModel->searchOutput(Yii::$app->request->getQueryParams());

        return $this->render('output', [
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
            if ($model->validate()) {
                $model->save();
                //create a data in stock total
                StockTotal::updateTotal($model->storeroom_id,$model->material_id,$model->actual_quantity);
                Yii::$app->session->setFlash('success', '新建成功！');
                $this->redirect("/stock/list");
            }
        }
        return $this->render('create', array(
            'model' => $model,'isNew'=>true,
        )); 
    }
    public function actionExport(){
        if(isset($_GET['mid']) && $_GET['mid'] != ""){
            $material_id = $_GET['mid'];
            $filename = Material::findOne($material_id)->name;
            $filename .= "入库报表.xls";
            $stocks = Stock::find()->where(['material_id'=>$material_id,'increase'=>Stock::IS_INCREASE,'owner_id'=>Yii::$app->user->id])->orderby(['material_id'=>SORT_DESC,'id'=>SORT_DESC])->all();
        }else{
            $filename = "入库报表.xls";
            $stocks = Stock::find()->where(['increase'=>Stock::IS_INCREASE,'owner_id'=>Yii::$app->user->id])->orderby(['id'=>SORT_DESC])->all();
        }
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1','物料品名')
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
        header('Content-Type: application/vnd.ms-excel;charset=utf-8');
        header('Content-Disposition: attachment;filename='.$filename);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
    public function actionExportoutput(){
        if(isset($_GET['mid']) && $_GET['mid'] != ""){
            $material_id = $_GET['mid'];
            $filename = Material::findOne($material_id)->name;
            $filename .= "出库报表.xls";
            $stocks = Stock::find()->where(['material_id'=>$material_id,'increase'=>Stock::IS_NOT_INCREASE,'owner_id'=>Yii::$app->user->id])->orderby(['material_id'=>SORT_DESC,'id'=>SORT_DESC])->all();
        }else{
            $filename = "出库报表.xls";
            $stocks = Stock::find()->where(['increase'=>Stock::IS_NOT_INCREASE,'owner_id'=>Yii::$app->user->id])->orderby(['id'=>SORT_DESC])->all();
        }
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1','物料品名')
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
                        ->setCellValue('E'.$i, (0 - $v->actual_quantity))
                        ->setCellValue('F'.$i, $v->stock_time)
                        ->setCellValue('G'.$i, $v->orders->viewid);
                        $i++;
        }
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel;charset=utf-8');
        header('Content-Disposition: attachment;filename='.$filename);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
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
}
