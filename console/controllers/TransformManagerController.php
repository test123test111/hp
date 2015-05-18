<?php
/**
 * Background and warehousing operations separately allocated background administrator privileges
 * Add new table manager_platform
 * now,table manager include managers only use in warehousing system,so wo need import table manager
 * data into manager platform  
 */
namespace console\controllers;

use Yii;
use yii\console\Controller;
use backend\models\Manager;
use backend\models\ManagerPlatform;

class TransformManagerController extends Controller{

    public $platform = 'cangchu';

    public function actionRun()
    {
        $managers = Manager::find()->all();
        foreach($managers as $manager){
            if($manager->id != Manager::SUPER_ADMIN){
                $model = new ManagerPlatform;
                $model->platform = $this->platform;
                $model->manager_id = $manager->id;
                $model->save();
            }
            
        }
    }
} 