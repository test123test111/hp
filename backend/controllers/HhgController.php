<?php

namespace backend\controllers;

use Yii;
use backend\models\Hhg;
use backend\models\search\HhgSearch;
use backend\components\BackendController;

class HhgController extends BackendController {
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
        $searchModel = new HhgSearch;
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
        $model = new Hhg;
        // collect user input data
        if (isset($_POST['Hhg'])) {
            // $model->setScenario('signup');
            $model->load($_POST);
            if ($model->validate()) {
                $model->save();
                Yii::$app->session->setFlash('success', '新建成功！');
                $this->redirect("/hhg/list");
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
        $owner = Hhg::findOne($id);
        return $this->render('view', [
            'owner' => $owner,
        ]);
    }
    /**
     * Displays the create page
     */
    public function actionUpdate($id) {
        $model = new Hhg;
        $id = $_GET['id'];
        if($id){
            $model = $this->loadModel($id);
            if(Yii::$app->request->isPost){
                $model->load(Yii::$app->request->post());
                // $model->setScenario('resetPassword');
                if($model->validate()){
                    $model->update();
                }
            }
        }
        return $this->render('create',['model'=>$model]);
    }
    public function actionDelete(){
        $id = $_GET['id'];
        $model = Hhg::findOne($id);
        $model->deleteUser();
        return $this->redirect("/hhg/list");
    }
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Hhg::findOne($id);
        if ($model === null) throw new CHttpException(404, 'The requested page does not exist.');
        
        return $model;
    }
}
