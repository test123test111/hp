<?php
namespace hhg\controllers;

use Yii;
use backend\models\Hhg;
use backend\models\search\HhgSearch;
use common\models\SendEmail;

class UserController extends \yii\web\Controller {

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
        $model = new Hhg;
        // collect user input data
        if (isset($_POST['Hhg'])) {
            // $model->setScenario('signup');
            $model->load($_POST);
            if ($model->validate()) {
                $model->save();
                Yii::$app->session->setFlash('success', '新建成功！');
                $this->redirect("/user/list");
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
        $model = new Hhg;
        $id = $_GET['id'];
        if($id){
            $model = $this->loadModel($id);
            if(Yii::$app->request->isPost){
                $model->load(Yii::$app->request->post());
                // $model->setScenario('resetPassword');
                if($model->validate()){
                    $model->update();
                    Yii::$app->session->setFlash('info', '修改成功!');
                }
            }
        }
        return $this->render('create',['model'=>$model]);
    }
    /**
     * Displays the page list
     */
    public function actionList() {
        $searchModel = new HhgSearch;
        list($data,$pages,$count) = $searchModel->getDataByHhg(Yii::$app->request->getQueryParams());

        return $this->render('list', [
             'results' => $data,
             'pages' => $pages,
             'count'=>$count,
             'params'=>Yii::$app->request->getQueryParams(),
        ]);
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
