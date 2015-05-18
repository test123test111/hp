<?php
namespace operate\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\models\Stock;

class Category extends ActiveRecord
{
    
    public static function tableName()
    {
        return "stock_category";
    }
    
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'update_time',
                ],
            ],
        ];
    }
    
    /**
     * 获取顶级类目
     */
    public static function getTopCategory() {
        $query = Category::find();
        $query->andWhere(['parent' => 0, 'valid' => 1]);
        $data = $query->all();
        return $data;
    }

    /**
     * 获取商品数量
     */
    public function getStockNum() {
        if ($this->parent ==0 ) {
            return Stock::find()->where(['category_parent_id' => $this->id])->count();
        } else {
            return Stock::find()->where(['category_sub_id' => $this->id])->count();
        }
    }

    public function attributeLabels(){
        return [
            'name' => '类目名称',
        ];
    }
}