<?php
namespace operate\models\search;
use Yii;
use yii\base\Model;
use operate\models\OpPosition;
use yii\data\ActiveDataProvider;

class OpPositionSearch extends OpPosition{

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name','status','type'],'safe'],
        ];
    }
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = OpPosition::find()->orderBy(['id'=>SORT_DESC]);
        if(isset($params['name']) && $params['name'] != ''){
            $query->andWhere(['like', 'name', $params['name']]);
        }
        if(isset($params['status']) && $params['status'] != ''){
            $query->andWhere(['status'=>$params['status']]);
        }else{
            $query->andWhere(['status'=>self::STATUS_ON_LINE]);
        }
        if(isset($params['type']) && $params['type'] != ''){
            $query->andWhere(['type'=>$params['type']]);
        }
        
        $count = $query->count();
        $pages = new \yii\data\Pagination(['totalCount' => $count]);
        $data = $query->offset($pages->offset)->all();

        return [$data,$pages,$count];
    }
}