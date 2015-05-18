<?php
namespace operate\models\search;
use operate\models\User;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class UserSearch extends User{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name','qq','phone','weixin_id'],'safe'],
        ];
    }

    public function search($params){
        $query = Static::find()->with('vipInfo');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'phone' => $this->phone,
            'weixin_id' => $this->weixin_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    public function getOptLink(){
        return '
            if(isset($model->vipInfo)){
                return \yii\helpers\Html::label("已添加");
             }else{
                return \yii\helpers\Html::a("＋添加","/uservip/add?id=$model->id");
             }
        ';
    }
}