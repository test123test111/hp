<?php
namespace operate\models\search;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;
use operate\models\Category;


class CategorySearch extends Category
{
    
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'],'safe'],
        ];
    }

    public static function search($paramArr)
    {
        $options = [
            'id'     => 0,
            'name'   => '',
            'parent' => 0,
        ];
        if (is_array($paramArr)) $options = array_merge($options, $paramArr);
        extract($options);
        
        $id     = (int)$id;
        $parent = (int)$parent;
        
        $query  = Category::find();
        $query->andWhere(['valid' => 1]);
        
		if ($id) {
            $query->andWhere(['id' => $id]);
		}
        if ($name) {
            $query->andWhere(['like', 'name', $name]);
		}
        if ($parent) {
            $query->andWhere(['parent' => $parent]);
		}
        
		$count  = $query->select('id')->distinct()->count();
	    $pages  = new Pagination(['defaultPageSize' => 15,'totalCount' => $count]);
	    $data   = [];
	    $query->select('*')->distinct()->offset($pages->offset)->limit($pages->limit)->orderby(['parent' => SORT_ASC,'id' => SORT_DESC]);
		$data = $query->all();
	    return [$data, $pages, $count];
    }
}