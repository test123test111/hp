<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Hhg;

/**
 * PostSearch represents the model behind the search form about `backend\models\Post`.
 */
class HhgSearch extends Hhg
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'email','phone','tell'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Hhg::find()->orderBy(['id'=>SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'tell' => $this->tell,
        ]);

        // $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    /**
     * [getDataByHhg description]
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public function getDataByHhg($params){
        $query = static::find()->orderBy(['id'=>SORT_DESC]);
        if(isset($params['name']) && $params['name'] != ""){
            $query->andWhere(['like','name',$params['name']]);
            $query->orWhere(['like','email',$params['name']]);
        }
        $count = $query->count();
        $pages = new \yii\data\Pagination(['totalCount' => $count,'defaultPageSize'=>20]);
        $ret = [];
        $query->offset($pages->offset)->limit(20);

        $data = $query->all();
        return [$data,$pages,$count];
    }
}
