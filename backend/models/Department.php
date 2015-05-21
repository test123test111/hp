<?php
namespace backend\models;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use common\models\Department as Dp;
/**
 * Class User
 * @package common\models
 *
 */
class Department extends Dp 
{	
	public function rules()
    {
        return [
        	['name','required'],
        	['name','unique'],
        ];
    }
    public function getOptLink(){
        return '
             return \yii\helpers\Html::a("编辑","/department/update/$model->id")."|".\yii\helpers\Html::a("设置分组","/department/setting?CategorySearch[department_id]=$model->id");
        ';
    }
    /**
     * save catefory for department 
     * @param  [type] $category [description]
     * @return [type]           [description]
     */
    public function saveCategory($category){
        $model = new Category;
        $model->department_id = $this->id;
        $model->name = $category['name'];
        if($model->save()){
            return true;
        }
        return false;
    }
}