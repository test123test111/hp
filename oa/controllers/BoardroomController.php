<?php

namespace oa\controllers;

use Yii;
use oa\models\Boardroom;
use backend\components\BackendController;

class BoardroomController extends BackendController {

    public $layout = false;

    public function actionCreate() {
        if (Yii::$app->request->isPost) {
            $model = new Boardroom;
            $request = Yii::$app->request;
            $model->name = strip_tags($request->post('name'));
            $model->floor = strip_tags($request->post('floor'));
            $model->people_num = strip_tags($request->post('people_num'));
            $model->created_time = time();
            $model->save();
            return $this->redirect('/boardroom/update/' . $model->id);
        }
        return $this->render('create', []);
    }

    public function actionList() {
        $boardroomModel = new Boardroom;
        $dataProvider = $boardroomModel->search(Yii::$app->request->getQueryParams());
        list($data, $pages, $count) = $dataProvider;
        return $this->render('list', [
                    'results' => $data,
                    'pages' => $pages,
                    'params' => Yii::$app->request->getQueryParams(),
                    'count' => $count,
        ]);
    }

    public function actionDelete($id) {
        $model = Boardroom::find()->where(['id' => (int)$id])->one();
        if ($model->delete()) {
            return $this->redirect("/boardroom/list");
        }
    }

    public function actionUpdate($id) {
        $model = Boardroom::find()->where(['id' => $id])->one();
        if (Yii::$app->request->isPost) {
            if ($model->online()) {
                $this->refresh();
                Yii::$app->session->setFlash('success', '更新成功!');
            } else {
                Yii::$app->session->setFlash('info', '更新失败!');
            }
        }
        return $this->render('edit', ['model' => $model]);
    }

}
