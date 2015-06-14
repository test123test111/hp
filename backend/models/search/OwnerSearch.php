<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Owner;
use common\models\Department;
use common\models\Category;
use common\models\ProductLine;
use common\models\ProductTwoLine;

/**
 * PostSearch represents the model behind the search form about `backend\models\Post`.
 */
class OwnerSearch extends Owner
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['english_name', 'email','phone','tell'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Owner::find()->with(['departments','categorys','productlines','producttwolines'])->orderBy(['id'=>SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'english_name' => $this->english_name,
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
        $query = static::find()->with(['departments','categorys','productlines','producttwolines','budgets'])->orderBy(['id'=>SORT_DESC]);
        if(isset($params['username']) && $params['username'] != ""){
            $query->andWhere(['like','english_name',$params['username']]);
            $query->orWhere(['like','email',$params['username']]);
        }
        if(isset($params['department']) && $params['department'] != ""){
            $department = Department::find()->where(['name'=>$params['department']])->one();
            if(!empty($department)){
                $query->andWhere(['department'=>$department->id]);
            }else{
                $query->andWhere(['department'=>'-1']);
            }
        }
        if(isset($params['category']) && $params['category'] != ""){
            $category = Category::find()->where(['name'=>$params['category']]);
            if(!empty($category)){
                $query->andWhere(['category'=>$category->id]);
            }else{
                $query->andWhere(['category'=>'-1']);
            }
        }
        if(isset($params['product_line']) && $params['product_line'] != ""){
            $productline = ProductLine::find()->where(['name'=>$params['product_line']])->one();
            if(empty($category)){
                $productline = ProductTwoLine::find()->where(['name'=>$params['product_line']])->one();
                if(empty($productline)){
                    $query->andWhere(['product_line'=>'-1']);
                }else{
                    $query->andWhere(['product_two_line'=>$productline->id]);
                }
            }else{
                $query->andWhere(['product_line'=>$productline->id]);
            }
        }
        $count = $query->count();
        $pages = new \yii\data\Pagination(['totalCount' => $count,'defaultPageSize'=>20]);
        $ret = [];
        $query->offset($pages->offset)->limit(20);

        $data = $query->all();
        return [$data,$pages,$count];
    }
}
