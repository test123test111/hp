<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Category;

/**
 * PostSearch represents the model behind the search form about `backend\models\Post`.
 */
class CategorySearch extends Category
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['department_id'], 'required'],
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
        $query = Category::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'department_id'=>$this->department_id,
            'name'=>$this->name,
        ]);
        return $dataProvider;
    }
    
}
