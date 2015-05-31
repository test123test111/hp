<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ProductTwoLine;

/**
 * PostSearch represents the model behind the search form about `backend\models\Post`.
 */
class ProductTwoLineSearch extends ProductTwoLine
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['product_line_id'], 'required'],
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
        $query = ProductTwoLine::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'product_line_id'=>$this->product_line_id,
            'name'=>$this->name,
        ]);
        return $dataProvider;
    }
    
}
