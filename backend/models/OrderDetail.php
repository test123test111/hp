<?php
namespace backend\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
use backend\components\BackendActiveRecord;
use backend\models\Stock;
use backend\models\OrderChannel;

class OrderDetail extends BackendActiveRecord {
    //是否通过物主审批
    const IS_OWNER_APPROVAL = 1;
    const IS_NOT_OWNER_APPROVAL = 0;
    const IS_REJECT_OWNER_APPROVAL = 2;
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
    	return $this->hasOne(Material::className(),['id'=>'material_id']);
    }
    public function getStoreroom(){
        return $this->hasOne(Storeroom::className(),['id'=>'storeroom_id']);
    }
    public function getOrders(){
        return $this->hasOne(Order::className(),['id'=>'order_id']);
    }
}