<?php

namespace customer\controllers;

use Yii;
use backend\models\Owner;
use backend\models\search\OwnerSearch;
use backend\components\BackendController;

class OwnerController extends BackendController {
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
    public function actionDisplaypassword(){
        return $this->render('resetpassword');
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
        list($data,$pages,$count) = $searchModel->searchDepartmentUser(Yii::$app->request->getQueryParams(),Yii::$app->user->id);

        return $this->render('list', [
             'results' => $data,
             'pages' => $pages,
             'count'=>$count,
             'params'=>Yii::$app->request->getQueryParams(),
        ]);
    }
    /**
     * Displays the create page
     */
    public function actionCreate() {
        $model = new Owner;
        // collect user input data
        if (isset($_POST['Owner'])) {
            $model->setScenario('signup');
            $model->load($_POST);
            if ($model->validate()) {
                $model->save();
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
        $model = new Owner;
        if(Yii::$app->request->isPost){
            $model = $this->loadModel(Yii::$app->user->id);
            if ($model->updateAttrs($_POST['Owner'])) {
                echo 0;
            }else{
                echo 1;
            }
        }
    }
    /**
     * action for user reset password
     */
    public function actionResetPassword(){
        $uid = Yii::$app->user->id;
        $model = $this->loadModel($uid);
        if(Yii::$app->request->isPost){
            $model->setScenario('resetPassword');
            $model->password = Yii::$app->request->post('password');
            if($model->save()){
                echo 0;
            }else{
                echo 1;
            }
        }
    }
    public function actionDelete(){
        $model = new Owner();
        $id = $_GET['id'];
        $model->deleteUser($id);
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
}
