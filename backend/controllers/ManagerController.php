<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\models\Manager;
use backend\models\ManagerPlatform;
use backend\models\search\ManagerSearch;
use backend\components\BackendController;

class ManagerController extends BackendController{

    /**
     * Displays the manager list
     */
    public function actionAdmin() {
        $searchModel = new ManagerSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('admin', [
            'dataProvider' => $dataProvider,
            'model' => $searchModel,
        ]);
    }
    /**
     * action for create manager
     */
    public function actionCreate()
    {
        $model = new Manager;
        $model->setScenario('signup');
        if($model->load(Yii::$app->request->post()) && $model->save()){
            //create manager platform
            $platform = new ManagerPlatform;
            $platform->manager_id = $model->id;
            $platform->platform = Yii::$app->id;
            if($platform->save()){
                return $this->redirect("/manager/admin");
            }
            
        }
        return $this->render('create',['model'=>$model]);
    }
    /**
     * action for delete manager
     * set manager's status as 0
     */
    public function actionDelete($id){
        $model = $this->loadModel($id);
        if($model->deleteUser($id)){
            return $this->redirect("/manager/admin");
        }
    }
    /**
     * action for update manager
     */
    public function actionUpdate(){
        $model = new Manager;
        $id = Yii::$app->request->get('id');
        if($id){
            $model = $this->loadModel($id);
            if (!empty($_POST)) {
                if ($model->updateAttrs($_POST['Manager'])) {
                    Yii::$app->session->setFlash('success', '修改成功！');
                }
            }
        }
        return $this->render('create',['model'=>$model]);
    }
    /**
     * action for user reset password
     */
    public function actionResetPassword(){
        $uid = Yii::$app->user->id;
        $model = $this->loadModel($uid);
        if(Yii::$app->request->isPost){
            $model->setScenario('resetPassword');
            $model->load(Yii::$app->request->post());
            if($model->save()){
                Yii::$app->session->setFlash('success', '修改成功！');
            }
        }
        return $this->render('resetpassword',['model'=>$model]);
    }
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Manager::findOne($id);
        if ($model === null)
        { 
            throw new \Exception("Error Processing Request", 404);
        }
        $platform = ManagerPlatform::getUserByPlatformAndUid(Yii::$app->id,$id);
        if(empty($platform)){
            throw new \Exception("Error Processing Request", 404);
        }
        return $model;
    }
}