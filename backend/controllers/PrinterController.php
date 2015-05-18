<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\models\Printer;
use backend\models\PrinterUser;
use backend\models\search\PrinterSearch;
use backend\models\search\PrinterUserSearch;
use backend\components\BackendController;

class PrinterController extends BackendController{

    /**
     * Displays the printer list
     */
    public function actionList() {
        $searchModel = new PrinterSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'model' => $searchModel,
        ]);
    }
    /**
     * action for create printer
     */
    public function actionCreate()
    {
        $model = new Printer;
        if($model->load(Yii::$app->request->post())){
            if ($model->validate() && $model->save()) {
                return $this->redirect("/printer/list");
            }
        }
        return $this->render('create',['model'=>$model,'isNew'=>true]);
    }
    /**
     * Displays the create page
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if(Yii::$app->request->isPost){
            if($model->type != Yii::$app->request->post('Printer')['type']){
                PrinterUser::updateAll(['type' => Yii::$app->request->post('Printer')['type']], ['printer_id'=>$id]);
            }
            $model->load(Yii::$app->request->post());
            if($model->validate()){
                $model->save();
                Yii::$app->session->setFlash('success', '修改成功！');
            }
        }
        return $this->render('create', array(
            'model' => $model,'isNew'=>false,
        ));
    }
    /**
     * view printer users
     * @return string
     */
    public function actionView()
    {
        $searchModel = new PrinterUserSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $id = Yii::$app->request->getQueryParams()['id'];
        $model = new PrinterUser();
        $printer = $this->loadModel($id);
        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
            if($model->validate() && $model->save()){
                return $this->redirect("/printer/view/{$id}");
            }
        }
        return $this->render(
            'view',
            [
                'dataProvider' => $dataProvider,
                'model' => $searchModel,
                'printer'=>$printer,
                'model' => $model,
            ]
        );
    }
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Printer::findOne($id);
        if ($model === null) throw new \Exception('The requested page does not exist.',404);
        
        return $model;
    }
}