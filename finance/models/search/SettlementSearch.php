<?php

namespace finance\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Settlement;

/**
 * PostSearch represents the model behind the search form about `backend\models\Post`.
 */
class SettlementSearch extends Settlement
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['status','settlement_time'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public static function getHistoryList($params)
    {
        $query = static::find()->orderBy(['id'=>SORT_DESC]);
        if(isset($params['begin_time']) && isset($params['end_time']) && $params['begin_time'] != '' && $params['end_time'] != ""){
            $begin_time = strtotime($params['begin_time']);
            $end_time = strtotime($params['end_time']);
            $query->andWhere('created_time >= :begin_time AND created_time <= :end_time',[':begin_time'=>$begin_time,"end_time"=>$end_time]);
        }
        $count = $query->count();
        $pages = new \yii\data\Pagination(['totalCount' => $count]);
        $query->offset($pages->offset)->limit(10);

        $results = $query->all();
        return [$results,$pages,$count];
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
