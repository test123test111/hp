<?php
namespace operate\controllers;

use backend\components\BackendController;

class GoodsController extends BackendController{     
	public $layout = false;
	/**
	      * Displays the manager list
	*/
    public function actionIndex() {
        return $this->render('index', [

        ]);
    }
    public function actionQuery() {
        return $this->render('query', [

        ]);
    }
    public function actionEdit() {
        return $this->render('edit', [

        ]);
    }
}