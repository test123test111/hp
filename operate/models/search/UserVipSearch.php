<?php
namespace operate\models\search;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use operate\models\UserVip;

class UserVipSearch extends UserVip{



    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['name','phone','qq','weixin'],'safe'],
        ];
    }

    public function search($params,$userids=[]){
        $query = Static::find();
        if(!empty($userids)){
            $query->andWhere(['user_id'=>$userids]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'phone' => $this->phone,
            'qq' => $this->qq,
            'weixin' => $this->weixin,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    public function getOptLink(){
        return '
             return \yii\helpers\Html::a("编辑","/uservip/edit?id=$model->id")."|".\yii\helpers\Html::a("详情","/uservip/detail?user_id=$model->user_id")."|".\yii\helpers\Html::a("购物车","/uservip/cart?user_id=$model->user_id");
        ';
    }


}