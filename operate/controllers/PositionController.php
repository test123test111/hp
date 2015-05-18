<?php
namespace operate\controllers;

use Yii;
use operate\models\OpPosition;
use operate\models\search\OpPositionSearch;
use operate\models\OpPositionDetail;
use backend\components\BackendController;
use common\components\Upload;
class PositionController extends BackendController{
  	public $layout = false;

  	/**
  	 * action for create a new position
  	 * @return [type] [description]
  	 */
  	public function actionCreate(){	
        $model = new OpPosition;

  		  if(Yii::$app->request->isPost){
            $model->setAttributes(Yii::$app->request->post('OpPosition'));
            if ($model->validate()) {
                $model->savePosition(Yii::$app->request->post('OpPositionDetail'));
                return $this->redirect('/position/update/'.$model->id);
            }
  		  }
        return $this->render('create',['model'=>$model]);
  	}
  	/**
  	 * action for position list
  	 * @return [type] [description]
  	 */
  	public function actionList(){
  		  $searchModel = new OpPositionSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        list($data,$pages,$count) = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('list', [
             'results' => $data,
             'pages' => $pages,
             'params'=>Yii::$app->request->getQueryParams(),
             'count'=>$count,
        ]);
  	}
  	/**
  	 * action for off line position
  	 * @return [type] [description]
  	 */
  	public function actionDelete($id){
  		  $model = $this->loadModel($id);
        if($model->offline()){
            return $this->redirect("/position/list");
        }
  	}
    /**
     * action for update position position detail 
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionUpdate($id){
        $model = $this->loadModel($id);
        $details = $model->getPositionDetails();
        if(Yii::$app->request->isPost){
            $detailMapper = new OpPositionDetail;
            $detailMapper->savePositionDetail(Yii::$app->request->post('OpPositionDetail'));
            if($model->online()){
                $this->refresh();
                Yii::$app->session->setFlash('success', '更新成功!');
            }else{
                Yii::$app->session->setFlash('info', '更新失败!');
            }
        }
        return $this->render('edit',['model'=>$model,'details'=>$details]);
    }
  	/**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = OpPosition::find()->with('details')->where(['id'=>$id])->one();
        if ($model === null)
        { 
            throw new \Exception("Error Processing Request", 404);
        }
        return $model;
    }
    /**
     * action for upload image 
     * @return [type] [description]
     */
    public function actionUploadfile(){
        $num = Yii::$app->request->post('num');
        if($_FILES){
            $model = new Upload;
            $result = $model->uploadImage($_FILES);
            if($result[0] == true){
                    echo <<<EOF
            <script>parent.stopSend("{$num}","{$result[2]}");</script>
EOF;
            }else{
                echo <<<EOF
            <script>alert("{$result[1]}");</script>
EOF;
            }
        }
    }
}