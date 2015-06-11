<?php
namespace customer\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
use customer\components\CustomerActiveRecord;
use customer\models\Stock;
use customer\models\OrderChannel;

class OrderDetail extends CustomerActiveRecord {
    const OWNER_APPROVAL = 1;
    const OWNER_NOT_APPROVAL = 0;
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'order_detail';
    }
    public function getMaterial(){
    	return $this->hasOne(Material::className(),['code'=>'goods_code']);
    }

}