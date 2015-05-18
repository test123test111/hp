<?php
namespace finance\controllers;

use backend\components\BackendController;

class BalanceController extends BackendController{
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
	public function actionAccount() {
		return $this->render('account', [

		]);
	}
	public function actionManual() {
		return $this->render('manual', [

		]);
	}
}