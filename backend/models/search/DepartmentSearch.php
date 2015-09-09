<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Department;

/**
 * PostSearch represents the model behind the search form about `backend\models\Post`.
 */
class DepartmentSearch extends Department
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            ['name', 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Department::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'name'=>$this->name,
        ]);
        /**
         * filter create time in list page you need follow this:
         * in display page attribute
         * 5:{
         *           'attribute':'create_time',
         *           'format':['date', 'php:Y-m-d H:i'],
         *           'filterType':constant('\\kartik\\grid\\GridView::FILTER_DATE_RANGE'),
         *     }
         * then,is model class
         * if($this->create_time != null){
         *   $date = explode(' - ', $this->create_time);
         *   list($begin,$end) = $this->formatRequestDate($date[0],$date[1]);
         *   $query->andWhere("create_time >= :begin_time and create_time <= :end_time",[":begin_time"=>$begin,":end_time"=>$end]);
         * }
         */
        
        return $dataProvider;
    }

    public function searchByHhg($params)
    {
        $query = Department::find();

        if(isset($params['name']) && $params['name'] != ""){
            $query->where(['name' => $params['name']]);
        }
        $count = $query->count();
        $pages = new \yii\data\Pagination(['totalCount' => $count,'defaultPageSize'=>20]);
        $ret = [];
        $query->offset($pages->offset)->limit(20);

        $data = $query->all();
        return [$data,$pages,$count];
        
    }
    
}
