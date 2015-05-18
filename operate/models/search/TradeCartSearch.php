<?php
namespace operate\models\search;
use operate\models\TradeCart;
use operate\models\User;
use operate\models\UserVip;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class TradeCartSearch extends TradeCart
{
    public function rules()
    {
        return [
            [['stock_id'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = Static::find();
        if (isset($params['user_id']) && !empty($params['user_id'])) {
            $query->andWhere(['user_id' => $params['user_id']]);
        }else{

            $allVipUserIds = UserVip::getAllUserIds();
            if(!empty($allVipUserIds)){
                $query->andWhere(['user_id'=>$allVipUserIds]);
            }
        }
        $query->andWhere(['>', 'number', 0]);
        $query->groupby('stock_id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'stock_id' => $this->stock_id,
        ]);


        return $dataProvider;
    }

    /*
     * 加入购物车的用户数页面链接
     */
    public function getCartLink(){
        return '
             return \yii\helpers\Html::a("$model->cartVipUsers","/uservip/cartuser?stock_id=$model->stock_id");
        ';
    }

}