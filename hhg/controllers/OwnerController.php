<?php
namespace hhg\controllers;

use Yii;
use backend\models\Owner;
use backend\models\search\OwnerSearch;
use backend\components\BackendController;
use common\models\Budget;
use hhg\models\Hhg;
use backend\models\Share;
use backend\models\Category;
use backend\models\ProductLine;
use backend\models\ProductTwoLine;
use common\models\SendEmail;

class OwnerController extends \yii\web\Controller {

    public $layout = false;
    
    public function actionDisplaypassword(){
        return $this->render('resetpassword');
    }
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['list', 'displaypassword','category','update','updatehp','export','budget','updatebudget','create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) echo $error['message'];
            else $this->render('404');
        }
    }
    /**
     * hhg create hp owner
     * @return [type] [description]
     */
    public function actionCreate()
    {
        $model = new Owner;
        // collect user input data
        if (isset($_POST['Owner'])) {
            // $model->setScenario('signup');
            $model->load($_POST);
            if($model->big_owner == null){
                $model->big_owner = Owner::IS_NOT_BIG_OWNER;
            }
            if ($model->validate()) {
                $model->save();
                if($model->big_owner == Owner::IS_BIG_OWNER){
                    //update material share
                    Share::updateMaterial($model->id,$model->category);
                    if($model->is_budget == Owner::IS_BUDGET && $model->budget != "0"){
                        Budget::updateOwnerBudget($model->id,$model->category,$model->budget);
                    }
                }
                $ret = [
                    'email'=>$model->email,
                    'password'=>$_POST['Owner']['password'],
                ];
                $sendEmail = new SendEmail;
                $sendEmail->template = 'createuser';
                $sendEmail->content = json_encode($ret);
                $sendEmail->created = date('Y-m-d H:i:s');
                $sendEmail->save();

                Yii::$app->session->setFlash('success', '新建成功！');
                $this->redirect("/owner/list");
            }
        }
        return $this->render('create', array(
            'model' => $model,'isNew'=>true,
        )); 
    }
    /**
     * Displays the create page
     */
    public function actionUpdate() {
        if(Yii::$app->request->isPost){
            $model = Hhg::findOne(Yii::$app->user->id);
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                if ($model->is_reset_password == 0 ) {
                    $model->is_reset_password = 1;
                    $model->save(false);
                }
                echo 0;
            }else{
                echo 1;
            }
        }
    }
    /**
     * Displays the page list
     */
    public function actionList() {
        $searchModel = new OwnerSearch;
        list($data,$pages,$count) = $searchModel->getDataByHhg(Yii::$app->request->getQueryParams());

        return $this->render('list', [
             'results' => $data,
             'pages' => $pages,
             'count'=>$count,
             'params'=>Yii::$app->request->getQueryParams(),
        ]);
    }
    public function actionCategory(){
        $level = Yii::$app->request->get('level');
        $id = Yii::$app->request->get('id');
        if($level == 2){
            $out = Category::getCategoryByPid($id);
            echo json_encode($out);
        }
        if($level == 3){
            $out = ProductLine::getCategoryByPid($id);
            echo json_encode($out);
        }   
        if($level == 4){
            $out = ProductTwoLine::getCategoryByPid($id);
            echo json_encode($out);
        }
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
                $out = ProductLine::getCategoryByPid($cat_id);
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
    public function actionProducttwoline(){
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
                $out = ProductTwoLine::getCategoryByPid($cat_id);
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
     * export hp user 
     * @return [type] [description]
     */
    public function actionExport(){
        $result = OwnerSearch::getImportData(Yii::$app->request->getQueryParams());
        $filename = '用户信息.csv';
        $filename = iconv('utf-8', 'GB2312', $filename );
        header("Content-type:text/csv;charset=utf-8");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        print(chr(0xEF).chr(0xBB).chr(0xBF));
        echo $result;
    }
    public function actionBudget(){
        if (!Yii::$app->request->isPost) {
            throw new HttpException(404, 'The requested page does not exist.');
        }
        if (Yii::$app->request->post('id')) {
            $model = Budget::find()->where(['owner_id'=>Yii::$app->request->post('id')])->orderBy(['id'=>SORT_DESC])->one();
            echo $this->renderPartial('budget', ['model' => $model,'id'=>Yii::$app->request->post('id')]);
        }
    }
    /**
     * Displays the create page
     */
    public function actionUpdatehp($id) {
        $model = new Owner;
        $id = $_GET['id'];
        if($id){
            $model = $this->loadModel($id);
            $flag1 = $model->big_owner;
            if(Yii::$app->request->isPost){
                $model->load(Yii::$app->request->post());
                // $model->setScenario('resetPassword');
                if($model->validate()){
                    $flag2 = $model->big_owner;
                    $model->update();
                    if($model->is_budget == Owner::IS_BUDGET && $model->budget != "0"){
                        Budget::updateOwnerBudget($model->id,$model->category,$model->budget);
                    }
                    if($model->big_owner == Owner::IS_BIG_OWNER){
                        //update material share
                        Share::updateMaterial($model->id,$model->category);
                    }else{
                        if($flag1 != $flag2){
                            Share::recove($model->id);
                        }
                    }
                }
            }
        }
        return $this->render('updatehp',['model'=>$model,'isNew'=>false]);
    }
    /**
     * [actionUpdatebudget description]
     * @return [type] [description]
     */
    public function actionUpdatebudget(){
        if(Yii::$app->request->isPost){
            $owner_id = Yii::$app->request->post('id');
            $owner = Owner::findOne($owner_id);
            $price = Yii::$app->request->post('price');
            Budget::updateOwnerBudget($owner_id,$owner->category,$price);
            echo 0;
        }
    }   
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Owner::findOne($id);
        if ($model === null) throw new CHttpException(404, 'The requested page does not exist.');
        
        return $model;
    }
}
