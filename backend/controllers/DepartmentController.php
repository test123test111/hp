<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\models\Department;
use backend\models\Category;
use backend\models\ProductLine;
use backend\models\search\DepartmentSearch;
use backend\models\search\CategorySearch;
use backend\models\search\ProductLineSearch;
use backend\components\BackendController;

class DepartmentController extends BackendController{
    public $layout = false;
    /**
     * Displays the manager list
     */
    public function actionList() {
        $model = new DepartmentSearch;
        $dataProvider = $model->search(Yii::$app->request->getQueryParams());
        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
            if($model->validate() && $model->save()){
                return $this->redirect("/department/list");
            }
        }
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }
    /**
     * action for create manager
     */
    public function actionCreate()
    {
        $model = new Department;
        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
            if($model->validate() && $model->save()){
                return $this->redirect("/department/list");
            }
        }
        return $this->render('create',['model'=>$model]);
    }
    /**
     * action for update manager platform
     */
    public function actionUpdate(){
        $model = new DepartmentSearch;
        $dataProvider = $model->search(Yii::$app->request->getQueryParams());
        $id = Yii::$app->request->get('id');
        $model = $this->loadModel($id);
        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
            if($model->update()){
                return $this->redirect("/department/list");
            }
        }
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }
    /**
     * action for setting department category
     * @return [type] [description]
     */
    public function actionSetting(){
        $category = new CategorySearch;
        $dataProvider = $category->search(Yii::$app->request->getQueryParams());
        $id = Yii::$app->request->get('CategorySearch')['department_id'];
        $model = $this->loadModel($id);
        if(Yii::$app->request->isPost){
            $category->load(Yii::$app->request->post());
            if($category->validate()){
                $model->saveCategory(Yii::$app->request->post()['CategorySearch']);
                return $this->redirect(Yii::$app->request->getReferrer());
            }
        }
        return $this->render('setting',['model'=>$model,'category'=>$category,'dataProvider'=>$dataProvider]);
    }
    /**
     * update category 
     * @return [type] [description]
     */
    public function actionUpdatecat(){
        $category = new CategorySearch;
        $dataProvider = $category->search(Yii::$app->request->getQueryParams());
        $category = CategorySearch::findOne(Yii::$app->request->get('id'));
        // $id = Yii::$app->request->get('CategorySearch')['department_id'];
        $model = $this->loadModel($category->department_id);
        if(Yii::$app->request->isPost){
            $category->load(Yii::$app->request->post());
            if($category->update()){
                return $this->redirect(Yii::$app->request->getReferrer());
            }
        }
        return $this->render('setting',['model'=>$model,'category'=>$category,'dataProvider'=>$dataProvider]);
    }
    /**
     * action for set product for category
     * @return [type] [description]
     */
    public function actionSettingProd(){
        $category = new ProductLineSearch;
        $dataProvider = $category->search(Yii::$app->request->getQueryParams());
        $id = Yii::$app->request->get('ProductLineSearch')['category_id'];
        $model = CategorySearch::findOne($id);
        if(Yii::$app->request->isPost){
            $category->load(Yii::$app->request->post());
            if($model->validate()){
                $model->saveProduceLine(Yii::$app->request->post()['ProductLineSearch']);
                return $this->redirect(Yii::$app->request->getReferrer());
            }
        }
        return $this->render('setting-prod',['model'=>$model,'category'=>$category,'dataProvider'=>$dataProvider]);
    }   
    /**
     * action for update productline 
     * @return [type] [description]
     */
    public function actionUpdateprod(){
        $category = new ProductLineSearch;
        $dataProvider = $category->search(Yii::$app->request->getQueryParams());
        $category = ProductLine::findOne(Yii::$app->request->get('id'));
        $id = Yii::$app->request->get('ProductLineSearch')['category_id'];
        $model = CategorySearch::findOne($category->category_id);
        if(Yii::$app->request->isPost){
            $category->load(Yii::$app->request->post());
            if($category->validate()){
                $category->update();
                return $this->redirect(Yii::$app->request->getReferrer());
            }
        }
        return $this->render('setting-prod',['model'=>$model,'category'=>$category,'dataProvider'=>$dataProvider]);
    }
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Department::findOne($id);
        if ($model === null)
        { 
            throw new \Exception("Error Processing Request", 404);
        }
        return $model;
    }
}