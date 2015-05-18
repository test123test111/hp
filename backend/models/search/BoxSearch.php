<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Box;

/**
 * PostSearch represents the model behind the search form about `backend\models\Post`.
 */
class BoxSearch extends Box
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['status'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($uid,$is_admin = true)
    {
    	$query = Box::find()->orderBy(['id'=>SORT_DESC]);
    	if(!$is_admin){
        	$query->andWhere(['created_uid'=>$uid]);
       	}
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }
 }