<?php
namespace operate\controllers;

use backend\components\BackendController;

class OrderController extends BackendController{     
	public $layout = false;
	/**
	      * Displays the manager list
	*/
    public function actionIndex() {
        return $this->render('index', [

        ]);
    }
    public function actionDetail() {
        return $this->render('detail', [

        ]);
    }

    public function actionQuery() {
        return $this->render('query', [

        ]);
    }

}