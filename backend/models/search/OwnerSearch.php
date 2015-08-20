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
use common\models\Share;

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
    public function searchDepartmentUser($params,$uid)
    {
        $owner = static::findOne($uid);
        if($owner->big_owner == self::IS_BIG_OWNER) {
            $query = static::find()->with(['departments','categorys','productlines','producttwolines','budgets','storeroom'])->orderBy(['id'=>SORT_DESC]);
            $query->andWhere(['department' => $owner->department]);
        } else {
            $query = static::find()->where(['id' => $uid]);
        }
        $count = $query->count();
        $pages = new \yii\data\Pagination(['totalCount' => $count,'defaultPageSize'=>20]);
        $ret = [];
        $query->offset($pages->offset)->limit(20);

        $data = $query->all();
        return [$data,$pages,$count];
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

    /**
     * [getDataByHhg description]
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public static function getImportData($params){
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
        $str = "序号,用户类型,名字,邮箱,移动电话,固定电话,所在城市/库位,部门,组别,一级产品线,二级产品线,备注\n";
        $offset = 0;
        $limit = 100;
        $data = [];
        $i = 1;
        while(true){
            $results = $query->limit($limit)->offset($offset)->all();
            if(empty($results)){
                break;
            }
            foreach($results as $key =>$result){
                $data[$i]['id'] = $i;
                if($result->big_owner == self::IS_BIG_OWNER){
                    $data[$i]['type'] = "管理员";
                }else{
                    $share = Share::find()->where(['owner_id'=>$result->id])->one();
                    if(!empty($share)){
                        $data[$i]['type'] = "所属人";
                    }else{
                        $data[$i]['type'] = "申请人";
                    }
                }
                
                $data[$i]['name'] = $result->english_name;
                $data[$i]['email'] = $result->email;
                $data[$i]['phone'] = $result->phone;
                $data[$i]['tell'] = $result->tell;
                $data[$i]['city'] = "";
                $data[$i]['department'] = $result->departments->name;
                $data[$i]['category'] = $result->categorys->name;
                $data[$i]['productline'] = $result->productlines->name;
                $data[$i]['producttwoline'] = $result->producttwolines->name;
                $data[$i]['info'] = "";
                $str .= $data[$i]['id'].",".$data[$i]['type'].",".$data[$i]['name'].",".$data[$i]['email'].",".$data[$i]['phone'].",".$data[$i]['tell'].",".$data[$i]['city'].",".$data[$i]['department'].",".$data[$i]['category'].",".$data[$i]['productline'].",".$data[$i]['producttwoline'].",".$data[$i]['info']."\r\n"; //用引文逗号分开
                $i++;
            }
           
            $offset += $limit;
            if ($offset > $count) {
                break;
            }
        }
        return $str;
    }
}
