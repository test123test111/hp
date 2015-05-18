<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PrinterUser;

/**
 * PostSearch represents the model behind the search form about `backend\models\Post`.
 */
class PrinterUserSearch extends PrinterUser
{
    public function rules()
    {
        return [
            // [['name', 'email',], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = PrinterUser::find()->where(['printer_id' => $params['id']])->with('managers')->orderBy(['id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        return $dataProvider;
    }
}
