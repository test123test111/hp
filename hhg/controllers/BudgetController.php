<?php
namespace hhg\controllers;

use Yii;
use common\models\NewBudget;
use common\models\NewBudgetAdjust;
use common\models\NewBudgetTotal;
use common\models\NewBudgetConsume;
use backend\models\Storeroom;
use hhg\models\Owner;

class BudgetController extends \yii\web\Controller {

    public $layout = false;
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','adjust','create','export'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    /**
     * budget index list
     * @return [type] [description]
     */
    public function actionIndex(){
        list($data,$pages,$count) = NewBudget::getAllOwnerBudget(Yii::$app->request->getQueryParams());
        return $this->render('index',[
            'results'=>$data,
            'pages'=>$pages,
            'count'=>$count,
            'storerooms'=>Storeroom::find()->all(),
            'owners'=>Owner::find()->all(),
            'params'=>Yii::$app->request->getQueryParams()
        ]);
    }
    /**
     * 
     * create a budget for owner 
     * @return [type] [description]
     */
    public function actionCreate(){
        $model = new NewBudget;
        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
            // $storeroom = Storeroom::findOne(Yii::$app->request->post('NewBudget')['storeroom_id']);
            // $date = Yii::$app->request->post('date');
            // $model->year = date('Y',strtotime($date));
            // if($storeroom->level == Storeroom::STOREROOM_LEVEL_IS_CENTER){
            //     $model->time = date('n',strtotime($date));
            // }else{
            //     $model->time = ceil((date('n',strtotime($date)))/3);
            // }
            $owner = Owner::findOne($model->owner_id);
            if (!empty($owner)) {
                $model->storeroom_id = $owner->storeroom_id;
            }
            $model->created_uid = Yii::$app->user->id;
            if($model->validate() && $model->save()){
                $model->createBudgetTotal();
                return $this->redirect('index');
            }
        }
        return $this->render('create',[
            'model'=>$model,
            'createusers'=>Owner::getCreateUsers(),
        ]);
    }
    /**
     * one owner budget adjust list
     * @return [type] [description]
     */
    public function actionAdjust($id){
        $budget = NewBudget::findOne($id);
        if(Yii::$app->request->isPost){
            $price = Yii::$app->request->post('price');
            if($price){
                $budget->updateAdjust($price,Yii::$app->user->id);
            }
        }
        // $budget = NewBudget::findOne($id);
        if($budget == null){
            throw new \Exception("Error Processing Request", 404);
        }
        return $this->render('adjust',['budget'=>$budget]);
    }
    /**
     * action for export budget 
     * @return [type] [description]
     */
    public function actionExport(){
        $result = NewBudget::getExportData(Yii::$app->request->getQueryParams());
        $filename = '预算报告.csv';
        $filename = iconv('utf-8', 'GB2312', $filename );
        header("Content-type:text/csv;charset=utf-8");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        print(chr(0xEF).chr(0xBB).chr(0xBF));
        echo $result;
    }
}