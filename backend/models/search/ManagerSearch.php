<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Manager;

/**
 * PostSearch represents the model behind the search form about `backend\models\Post`.
 */
class ManagerSearch extends Manager
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['username','email','status'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Manager::find()->joinWith(['managerPlatforms'=>function($query){
            $query->where(['platform'=>Yii::$app->id]);
        }]);

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
    /**
     * format request time 
     * @param [type] $begin_time [description]
     * @param [type] $end_time   [description]
     * @return  time
     */
    public function formatRequestDate($begin_time,$end_time){
        $startDateObject = new \DateTime($begin_time);
        $endDateObject = new \DateTime($end_time);
        $endDateObject->modify('+1 day');
        $startDate = strtotime($startDateObject->format('Y-m-d H:i:s'));
        $endDate = strtotime($endDateObject->format('Y-m-d H:i:s'));
        return [$startDate,$endDate];
    }
}
