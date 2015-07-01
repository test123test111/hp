<?php
namespace console\controllers;
use Yii;
use yii\console\Controller;
use common\models\Share;
use backend\models\Owner;
use common\models\Category;
use backend\models\Material;
use backend\models\StockTotal;
class ShareCategoryController extends Controller{
    /**
     * push user unread message
     * record the last dateline
     * request push api 
     */
    public function actionRun(){
        $offset = 0;
        $limit = 100;
        $data = [];
        $i = 1;
        $count = Share::find()->count();
        while(true){
            $results = Share::find()->limit($limit)->offset($offset)->all();
            if(empty($results)){
                break;
            }
            foreach($results as $key =>$result){
                $owner_id = $result->owner_id;
                $material_id = $result->material_id;
                $owner = Owner::findOne($owner_id);
                $cat_id = 0;
                $property_id = 0;
                if(!empty($owner)){
                    $category = Category::findOne($owner->category);
                    if(!empty($category)){
                        $cat_id = $category->id;
                    }
                }
                $material = Material::findOne($material_id);
                if(!empty($material)){
                    $property_id = $material->property;
                }
                $result->category = $cat_id;
                $result->property = $property_id;
                $result->update(false);
            }
           
            $offset += $limit;
            if ($offset > $count) {
                break;
            }
        }
    }
    public function actionRun2(){
        $offset = 0;
        $limit = 100;
        $data = [];
        $i = 1;
        $count = StockTotal::find()->count();
        while(true){
            $results = StockTotal::find()->limit($limit)->offset($offset)->all();
            if(empty($results)){
                break;
            }
            foreach($results as $key =>$result){
                $owner_id = $result->owner_id;
                $material_id = $result->material_id;
                $owner = Owner::findOne($owner_id);
                $cat_id = 0;
                $property_id = 0;
                if(!empty($owner)){
                    $category = Category::findOne($owner->category);
                    if(!empty($category)){
                        $cat_id = $category->id;
                    }
                }
                $material = Material::findOne($material_id);
                if(!empty($material)){
                    $property_id = $material->property;
                }
                $result->category = $cat_id;
                $result->property = $property_id;
                $result->update(false);
            }
           
            $offset += $limit;
            if ($offset > $count) {
                break;
            }
        }
    }
}