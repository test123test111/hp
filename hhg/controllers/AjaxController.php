<?php

namespace customer\controllers;

use Yii;
use backend\models\Material;
use customer\components\CustomerController;

class AjaxController extends CustomerController {
    public $enableCsrfValidation;
    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $this->enableCsrfValidation = false;
        $material = Material::findOne($id);
        echo $this->renderPartial('material',['material'=>$material]);
        // echo yii\helpers\ArrayHelper::toArray($material);
    }
    /**
     * action for new order need approval 
     * icon flash
     * @return [type] [description]
     */
    public function actionNeworder(){
        echo Approval::getNewArroval();
    }
}
