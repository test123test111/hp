<?php

namespace backend\controllers;

use Yii;
use backend\models\News;
use backend\models\search\NewsSearch;
use backend\components\BackendController;

class NewsController extends BackendController {
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
        $searchModel = new NewsSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'model' => $searchModel,
        ]);
    }
    /**
     * Displays the create page
     */
    public function actionCreate() {
        $model = new News;
        // collect user input data
        if (isset($_POST['News'])) {
            $model->load($_POST);
            if ($model->validate()) {
                // $model->created_uid = 100;
                $model->save();
                Yii::$app->session->setFlash('success', '新建成功！');
                $this->redirect("/news/list");
            }
        }
        return $this->render('create', array(
            'model' => $model,'isNew'=>true,
        )); 
    }
    /**
     * Displays the create page
     */
    public function actionUpdate($id) {
        $model = new News;
        $id = $_GET['id'];
        $model = $this->loadModel($id);
        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
            if($model->validate() && $model->save()){
                Yii::$app->session->setFlash('success', '修改成功！');
            }
        }
        return $this->render('create',['model'=>$model]);
    }
    /**
     * [actionDelete description]
     * @return [type] [description]
     */
    public function actionDelete($id){
        $model = $this->loadModel($id);
        $model->status = News::STATUS_DELETED;
        $model->update();
        return $this->redirect('/news/list');
    }
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = News::findOne($id);
        if ($model === null) throw new CHttpException(404, 'The requested page does not exist.');
        
        return $model;
    }
}
