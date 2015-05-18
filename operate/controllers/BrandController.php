<?php
namespace operate\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\components\BackendController;
use operate\models\Brand;
use operate\models\search\BrandSearch;
use backend\models\Stock;

class BrandController extends BackendController
{
    public $layout = false;
    
    /*
     * 商品品牌列表
     */
    public function actionList()
    {
        $fiterTypeArray = ['id' => '品牌ID','name' => '品牌名称'];
        
        $content           = Yii::$app->request->get('content');
        $filterType        = Yii::$app->request->get('filterType');
        $defaultFilterType = 'name';
        $filterTypeName    = $fiterTypeArray[$defaultFilterType];
        
        $paramArr = [];
        
        if (array_key_exists($filterType, $fiterTypeArray)) {
            $paramArr[$filterType] = $content;
            $filterTypeName        = $fiterTypeArray[$filterType];
        }
        
        list($data,$pages,$count) =  BrandSearch::search($paramArr);
 
	    return $this->render('list', [
	        'data'              => $data,
	        'pages'             => $pages,
	        'params'            => Yii::$app->request->getQueryParams(),
	        'count'             => $count,
            'filterTypeName'    => $filterTypeName,
            'fiterTypeArray'    => $fiterTypeArray,
            'defaultFilterType' => $defaultFilterType,
	    ]);
    }
    
    /*
     * 商品品牌创建
     */
    public function actionCreate()
    {
        $model = new Brand;
        if (Yii::$app->request->isPost) {
            $model->ename = Yii::$app->request->post('Brand')['ename'];
            $model->cname = Yii::$app->request->post('Brand')['cname'];
            $model->create_username = Yii::$app->user->id;
            $model->create_usertype = 1;
            $model->status = 1;
            if ($model->ename && $model->save()) {
                return $this->redirect("/brand/list");
            }
        }
        return $this->render('create', ['model' => $model, 'isCreate' => true]);
    }
    
    /*
     * 商品品牌更新
     */
    public function actionUpdate($id)
    {
        $model = Brand::findOne($id);
        if (!$model) return $this->redirect("/brand/list");
        if (Yii::$app->request->isPost) {
            $model->ename = Yii::$app->request->post('Brand')['ename'];
            $model->cname = Yii::$app->request->post('Brand')['cname'];
            if ($model->ename && $model->save()) {
                return $this->redirect("/brand/list");
            }
        }
        return $this->render('create', ['model' => $model, 'isCreate' => false]);
    }
    
    /*
     * 商品品牌删除
     */
    public function actionDelete($id)
    {
        $model = Brand::findOne($id);
        $model->status = 0;
        $model->save();
        #清除产品对应的品牌关系
        Stock::updateAll(['brand_id' => 0],['brand_id' => $id]);
        return $this->redirect("/brand/list");
    }
}