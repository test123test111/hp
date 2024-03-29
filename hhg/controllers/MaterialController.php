<?php
namespace hhg\controllers;

use Yii;
use backend\models\Material;
use hhg\models\Stock;
use hhg\models\StockTotal;
use hhg\models\Storeroom;
use hhg\models\search\MaterialSearch;
use hhg\models\search\StockSearch;
use backend\models\Upload;
use common\models\Share;
use common\models\Category;
use hhg\models\Owner;
class MaterialController extends \yii\web\Controller {
    public $layout = false;
    public $enableCsrfValidation;
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'list','detail','export','view','share','updateshare','change','changewarning'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actions(){
        return [
            'error'=>[
                'class'=>'yii\web\ErrorAction',
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
        list($data,$pages,$count) = Stock::getMyData(Yii::$app->request->getQueryParams());
        $owners = Share::find()->select('owner_id')->distinct('owner_id')->with('owners')->where(['to_customer_id'=>Yii::$app->user->id,'status'=>Share::STATUS_IS_NORMAL])->all();
        return $this->render('list', [
             'results' => $data,
             'pages' => $pages,
             'count'=>$count,
             'params'=>Yii::$app->request->getQueryParams(),
             'storerooms'=>Storeroom::find()->all(),
             'ownersData'=>Owner::find()->all(),
             'property'=>(new Material())->getCanUseProperty(),
        ]);
    }
    /**
     * [actionShare description]
     * @return [type] [description]
     */
    public function actionShare(){
        if(Yii::$app->request->isPost){
            $uid = Yii::$app->user->id;
            $user = Owner::findOne($uid);
            $material_id = Yii::$app->request->post('material_id');
            $storeroom_id = Yii::$app->request->post('storeroom_id');
            if(!empty($user)){
                $category_id = $user->department;
                $users = Owner::find()->where(['department'=>$category_id])->andWhere(['<>','id',$uid])->all();
                $shares = Share::find()->select('to_customer_id')->where(['material_id'=>$material_id,'owner_id'=>$uid,'storeroom_id'=>$storeroom_id,'status'=>Share::STATUS_IS_NORMAL])->andWhere(['<>','to_customer_id',$uid])->column();
                echo $this->renderPartial('share',['users'=>$users,'shares'=>$shares,'material_id'=>$material_id,'storeroom_id'=>$storeroom_id]);
            }
        }
    }
    /**
     * [actionShare description]
     * @return [type] [description]
     */
    public function actionChange(){
        if(Yii::$app->request->isPost){
            $material_id = Yii::$app->request->post('material_id');
            $storeroom_id = Yii::$app->request->post('storeroom_id');
            $quantity = StockTotal::getTotalNum($material_id,$storeroom_id);
            echo $this->renderPartial('change',['material_id'=>$material_id,'storeroom_id'=>$storeroom_id,'quantity'=>$quantity]);
        }
    }
    /**
     * [actionShare description]
     * @return [type] [description]
     */
    public function actionChangewarning(){
        if(Yii::$app->request->isPost){
            $material_id = Yii::$app->request->post('material_id');
            $storeroom_id = Yii::$app->request->post('storeroom_id');
            $num = Yii::$app->request->post('num');
            StockTotal::updateAll(['warning_quantity'=>$num],['storeroom_id'=>$storeroom_id,'material_id'=>$material_id]);
            echo 1;
        }
    }
    /**
     * action for update owner shares 
     * @return [type] [description]
     */
    public function actionUpdateshare(){
        if(Yii::$app->request->isPost){
            $material_id = Yii::$app->request->post('material_id');
            $storeroom_id = Yii::$app->request->post('storeroom_id');
            $uid = Yii::$app->user->id;
            $to_uids = Yii::$app->request->post('user_ids');
            Share::updateShareByOwnerId($material_id,$storeroom_id,$uid,$to_uids);
            echo 1;
        }
    }
    /**
     * [actionDetail description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionDetail(){
        $searchModel = new StockSearch;
        $dataProvider = $searchModel->searchDetail(Yii::$app->request->getQueryParams());

        return $this->render('detail', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    public function actionExport(){
        if(isset($_GET['mid']) && $_GET['mid'] != 0){
            $material_id = $_GET['mid'];
            $filename = Material::findOne($material_id)->name;
            $stocks = Stock::find()->where(['material_id'=>$material_id])->orderby(['id'=>SORT_DESC])->all();
            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1','出入库标识')
                        ->setCellValue('B1','物料')
                        ->setCellValue('C1','仓库')
                        ->setCellValue('D1','所属人')
                        ->setCellValue('E1','预计入库数量')
                        ->setCellValue('F1','实际入库数量')
                        ->setCellValue('G1','入库时间')
                        ->setCellValue('H1','送货方');
            $i=2;
            foreach($stocks as $v)
            {
                $increase = $v->increase == Stock::IS_NOT_INCREASE ? "出库" :"入库";
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$i, $increase)
                            ->setCellValue('B'.$i, $v->material->name)
                            ->setCellValue('C'.$i, $v->storeroom->name)
                            ->setCellValue('D'.$i, $v->owners->english_name)
                            ->setCellValue('E'.$i, $v->forecast_quantity)
                            ->setCellValue('F'.$i, $v->actual_quantity)
                            ->setCellValue('G'.$i, $v->stock_time)
                            ->setCellValue('H'.$i, $v->delivery);
                            $i++;
            }
            $objPHPExcel->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel;charset=utf-8');
            header('Content-Disposition: attachment;filename='.($filename."库存报告.xls").'');
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        }
    }
    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     */
    public function actionView()
    {   
        $id = Yii::$app->request->get('id');
        $params = Yii::$app->request->getQueryParams();
        // $storeroom_id = Yii::$app->request->get('sid');
        if(!isset($params['sid']) || $params['sid'] == ""){
            return $this->render('error');
        }
        $uid = Yii::$app->user->id;
        $material = Material::findOne($id);
        return $this->render('view', [
            'material' => $material,
            'storerooms'=>Stock::getStockByUidAndMaterialId($uid,$id),
            'storeroom'=>Storeroom::findOne($params['sid']),
            'stocktotal'=>StockTotal::find()->where(['material_id'=>$material->id,'storeroom_id'=>$params['sid']])->one(),
            'owner'=>Owner::findOne($material->owner_id),
            'sid'=>$params['sid'],
        ]);
    }
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Material::findOne($id);
        if ($model === null) throw new CHttpException(404, 'The requested page does not exist.');
        
        return $model;
    }
}
