<?php
namespace hhg\controllers;

use Yii;
use common\models\NewBudget;
use common\models\NewBudgetAdjust;
use common\models\NewBudgetTotal;
use common\models\NewBudgetConsume;
use backend\models\Storeroom;
use backend\models\Owner;

class BudgetController extends \yii\web\Controller {

    public $layout = false;
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','adjust'],
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
        ]);
    }
    /**
     * 13935816676
     * create a budget for owner 
     * @return [type] [description]
     */
    public function actionCreate(){
        $model = new NewBudget;
        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
            $storeroom = Storeroom::findOne(Yii::$app->request->post('NewBudget')['storeroom_id']);
            $date = Yii::$app->request->post('date');
            $model->year = date('Y',strtotime($date));
            if($storeroom->level == Storeroom::STOREROOM_LEVEL_IS_CENTER){
                $model->time = ceil((date('n',strtotime($date)))/3);
            }else{
                $model->time = date('n',strtotime($date));
            }
            if($model->validate() && $model->save()){
                $model->createBudgetTotal();
            }   
        }
        return $this->render('create',[
            'model'=>$model,
        ]);
    }
    /**
     * one owner budget adjust list
     * @return [type] [description]
     */
    public function actionAdjust($id){
        if(Yii::$app->request->isPost){
            $price = Yii::$app->request->post('price');
            if($price){
                $budget->updateAdjust($price);
            }
        }
        $budget = NewBudget::findOne($id);
        if($budget == null){
            throw new \Exception("Error Processing Request", 404);
        }
        return $this->render('adjust',['budget'=>$budget]);
    }

}