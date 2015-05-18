<?php
namespace operate\models\search;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;
use operate\models\Brand;


class BrandSearch extends Brand
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['ename','cname'],'safe'],
        ];
    }

    public static function search($paramArr)
    {
        $options = [
            'id'   => 0,
            'name' => '',
        ];
        if (is_array($paramArr)) $options = array_merge($options, $paramArr);
        extract($options);
        
        $id    = (int)$id;
        $query = Brand::find();
        $query->andWhere(['status' => 1]);
		
		if ($id) {
            $query->andWhere(['id' => $id]);
		}
        
		if ($name) {
            $query->andWhere(['or',['like', 'cname', $name],['like', 'ename', $name]]);
		}
        
		$count  = $query->select('id')->distinct()->count();
	    $pages  = new Pagination(['defaultPageSize' => 15,'totalCount' => $count]);
	    $data   = [];
	    $query->select('*')->distinct()->offset($pages->offset)->limit($pages->limit)->orderby(['id' => SORT_DESC]);
		$data = $query->all();
	    return [$data, $pages, $count];
    }
}