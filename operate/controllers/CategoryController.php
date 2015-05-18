<?php
namespace operate\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\components\BackendController;
use operate\models\Category;
use operate\models\search\CategorySearch;
use common\components\Upload;
use backend\models\Stock;

class CategoryController extends BackendController
{
    public $layout = false;
    
    /*
     * 商品类目列表
     */
    public function actionList()
    {
        $fiterTypeArray = ['id' => '类目ID','name' => '类目名称'];
        
        $params            = Yii::$app->request->getQueryParams();
        $content           = Yii::$app->request->get('content');
        $filterType        = Yii::$app->request->get('filterType');
        $topCategoryId     = (int)Yii::$app->request->get('topCategoryId');
        $defaultFilterType = 'name';
        $filterTypeName    = $fiterTypeArray[$defaultFilterType];
        
        $paramArr = [];
        
        if (array_key_exists($filterType, $fiterTypeArray)) {
            $paramArr[$filterType] = $content;
            $filterTypeName        = $fiterTypeArray[$filterType];
        }
        if ($topCategoryId) {
            $paramArr['parent'] = $topCategoryId;
        }
        
        #获取顶级类目
        $topCategory = Category::getTopCategory();
        array_unshift($topCategory, ['id' => 0, 'name' => '全部']);
        $topCategoryArr = [];
        $topCategoryUrlArr = [];
        if ($topCategory) {
            $filterParamArr = $params;
            #筛选的时候不带入分页参数
            unset($filterParamArr['page']);
            foreach ($topCategory as $key => $value) {
                if ($value['id'] == 0) {
                    unset($filterParamArr['topCategoryId']);
                } else {
                    $filterParamArr['topCategoryId'] = $value['id'];
                }
                $topCategoryArr[$value['id']] = $value;
                $paramUrl = http_build_query($filterParamArr);
                $paramUrl = $paramUrl ? '?'.$paramUrl : '';
                $topCategoryUrlArr[$value['id']] = $paramUrl;
            }
        }
        
        list($data,$pages,$count) = CategorySearch::search($paramArr);
        
        if ($topCategoryId && array_key_exists($topCategoryId, $topCategoryArr)) {
            array_unshift($data, $topCategoryArr[$topCategoryId]);
            $count = $count + 1;
        }
        
	    return $this->render('list', [
	        'data'              => $data,
	        'pages'             => $pages,
	        'params'            => $params,
	        'count'             => $count,
            'filterTypeName'    => $filterTypeName,
            'fiterTypeArray'    => $fiterTypeArray,
            'topCategoryArr'    => $topCategoryArr,
            'topCategoryId'     => $topCategoryId,
            'topCategoryUrlArr' => $topCategoryUrlArr,
            'defaultFilterType' => $defaultFilterType,
	    ]);
    }
    
    /*
     * 商品类目创建
     */
    public function actionCreate()
    {
        $model = new Category;
        #获取顶级类目
        $topCategory = Category::getTopCategory();
        $topCategoryArr = [];
        if ($topCategory) {
            foreach ($topCategory as $key => $value) {
                $topCategoryArr[$value['id']] = $value;
            }
        }
        if (Yii::$app->request->isPost) {
            $ctype               = (int)Yii::$app->request->post('Category')['ctype'];
            $parent              = (int)Yii::$app->request->post('Category')['parent'];
            $model->name         = Yii::$app->request->post('Category')['name'];
            $model->icon         = Yii::$app->request->post('Category')['icon'];
            $model->position_id  = (int)Yii::$app->request->post('Category')['position_id'];
            $model->parent       = $ctype ? $parent : 0;
            if ($model->name && $model->save()) {
                return $this->redirect("/category/list");
            }
        }
        return $this->render('create', [
            'topCategoryArr' => $topCategoryArr,
            'model'          => $model,
            'isCreate'       => true,
        ]);
    }
    
    /*
     * 商品类目更新
     */
    public function actionUpdate($id)
    {
        $model = Category::findOne($id);
        #获取顶级类目
        $topCategory = Category::getTopCategory();
        $topCategoryArr = [];
        if ($topCategory) {
            foreach ($topCategory as $key => $value) {
                if ($id == $value['id']) continue;
                $topCategoryArr[$value['id']] = $value;
            }
        }
        if (!$model) return $this->redirect("/category/list");
        if (Yii::$app->request->isPost) {
            $ctype               = (int)Yii::$app->request->post('Category')['ctype'];
            $parent              = (int)Yii::$app->request->post('Category')['parent'];
            $model->name         = Yii::$app->request->post('Category')['name'];
            $model->icon         = Yii::$app->request->post('Category')['icon'];
            $model->position_id  = (int)Yii::$app->request->post('Category')['position_id'];
            $model->parent       = $ctype ? $parent : 0;
            if ($model->name && $model->save()) {
                $backUrl = Yii::$app->request->post('backUrl');
                if ($backUrl) {
                    return $this->redirect($backUrl);
                } else {
                    return $this->redirect("/category/list");
                }
            }
        }
        return $this->render('create', [
            'topCategoryArr' => $topCategoryArr,
            'model'          => $model,
            'isCreate'       => false,
        ]);
    }
    
    /*
     * 商品类目删除
     */
    public function actionDelete($id)
    {
        $model = Category::findOne($id);
        $model->valid = 0;
        $model->save();
        #清除产品对应的子类目关系
        if ($model->parent != 0) {
            Stock::updateAll(['category_sub_id' => 0],['category_sub_id' => $id]);
        }
        return $this->redirect("/category/list");
    }
    
    /**
     * 类目icon上传
     */
    public function actionUploadfile()
    {
        $num = Yii::$app->request->post('num');
        if ($_FILES) {
            $model = new Upload;
            $result = $model->uploadImage($_FILES);
            if ($result[0] == true) {
                    echo <<<EOF
            <script>parent.stopSend("{$num}","{$result[2]}");</script>
EOF;
            } else {
                echo <<<EOF
            <script>alert("{$result[1]}");</script>
EOF;
            }
        }
    }
}