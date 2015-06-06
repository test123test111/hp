<?php

namespace backend\controllers;

use Yii;
use backend\models\Owner;
use backend\models\Category;
use backend\models\ProductLine;
use backend\models\ProductTwoLine;
use backend\models\search\OwnerSearch;
use backend\components\BackendController;
use backend\models\Share;
use common\models\Budget;

class OwnerController extends BackendController {
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
            else $this->render('404');
        }
    }
    /**
     * Displays the page list
     */
    public function actionList() {
        $searchModel = new OwnerSearch;
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
        $model = new Owner;
        // collect user input data
        if (isset($_POST['Owner'])) {
            // $model->setScenario('signup');
            $model->load($_POST);
            if ($model->validate()) {
                $model->save();
                if($model->big_owner == Owner::IS_BIG_OWNER){
                    //update material share
                    Share::updateMaterial($model->id,$model->category);
                    if($model->is_budget == Owner::IS_BUDGET && $model->budget != "0"){
                        Budget::updateOwnerBudget($model->id,$model->budget);
                    }
                }
                Yii::$app->session->setFlash('success', '新建成功！');
                $this->redirect("/owner/list");
            }
        }
        return $this->render('create', array(
            'model' => $model,'isNew'=>true,
        )); 
    }
    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $owner = Owner::findOne($id);
        return $this->render('view', [
            'owner' => $owner,
        ]);
    }
    /**
     * Displays the create page
     */
    public function actionUpdate($id) {
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
                    if($model->big_owner == Owner::IS_BIG_OWNER){
                        //update material share
                        Share::updateMaterial($model->id,$model->category);
                        // if($model->is_budget == Owner::IS_BUDGET && $model->budget != "0"){
                        //     Budget::updateOwnerBudget($model->id,$model->budget);
                        // }
                    }else{
                        if($flag1 != $flag2){
                            Share::recove($model->id);
                        }
                    }
                }
            }
        }
        return $this->render('create',['model'=>$model]);
    }
    /**
     * action for update big owner budget 
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionUpdatebudget($id){
        $model = $this->loadModel($id);
        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
            Budget::updateOwnerBudget($model->id,$model->budget);
            Yii::$app->session->setFlash('success', '修改预算成功');
        }
        return $this->render('updatebudget',['model'=>$model]);
    }
    public function actionDelete(){
        $id = $_GET['id'];
        $model = Owner::findOne($id);
        $model->deleteUser();
        return $this->redirect("/owner/list");
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
    public function actionCategory(){
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
                $out = Category::getCategoryByPid($cat_id);
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
}
