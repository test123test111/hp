<?php
namespace backend\models;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use common\models\Category as Cg;
/**
 * Class User
 * @package common\models
 *
 */
class Category extends Cg 
{	
	public function rules()
    {
        return [
        	[['department_id','name'],'required'],
        	['name','unique'],
        ];
    }
    /**
     * save product line for category
     * @param  [type] $produceLine [description]
     * @return [type]              [description]
     */
    public function saveProduceLine($produceLine){
    	$model = new ProductLine;
    	$model->category_id = $this->id;
    	$model->name = $produceLine['name'];
    	if($model->save()){
    		return true;
    	}
    	return false;
    }
    public function getOptLink(){
        return '
             return \yii\helpers\Html::a("编辑","/department/updatecat/$model->id")."|".\yii\helpers\Html::a("设置产品线","/department/setting-prod?ProductLineSearch[category_id]=$model->id");
        ';
    }
    public static function getCategoryByPid($pro_id){
        $city = static::find()->where(['department_id'=>$pro_id])->asArray()->all();
        $ret = [];
        return array_map(function($a) use ($ret){
            $ret = ['id'=>$a['id'],'name'=>$a['name']];
            return $ret;
        },$city);

        // return \yii\helpers\ArrayHelper::map($city,'id','name');
    }
}