<?php

namespace operate\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\search\ManagerSearch as Ms;

/**
 * PostSearch represents the model behind the search form about `operate\models\Post`.
 */
class ManagerSearch extends Ms
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['username','email','status','platform'], 'safe'],
        ];
    }
    public function search($params)
    {
        $query = Static::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status'=>$this->status,
        ]);
        if(isset($this->platform) && !empty($this->platform)){
            $query->joinWith(['managerPlatforms'=>function($query){
                $query->where(['platform'=>$this->platform]);
            }]);
        }
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
        
        $query->andFilterWhere(['like', 'username', $this->username]);
        $query->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
