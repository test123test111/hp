<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ProductLine;

/**
 * PostSearch represents the model behind the search form about `backend\models\Post`.
 */
class ProductLineSearch extends ProductLine
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['category_id'], 'required'],
            ['name','safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ProductLine::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'category_id'=>$this->category_id,
            'name'=>$this->name,
        ]);
        return $dataProvider;
    }
    
}
