<?php
namespace hhg\controllers;

use Yii;
use backend\models\Owner;
use backend\models\search\OwnerSearch;
use backend\components\BackendController;
use common\models\Budget;
class OwnerController extends \yii\web\Controller {

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
            else $this->render('404');
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