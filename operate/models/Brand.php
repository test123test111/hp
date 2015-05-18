<?php
namespace operate\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use backend\models\Stock;

class Brand extends ActiveRecord
{
    
    public static function tableName()
    {
        return "stock_brand";
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
     * 获取商品数量
     */
    public function getStockNum() {
        return Stock::find()->where(['brand_id' => $this->id])->count();
    }
    
    public function attributeLabels(){
        return [
            'ename' => '品牌原名',
            'cname' => '品牌译名',
        ];
    }
}