<?php
namespace backend\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
use backend\components\BackendActiveRecord;
class StockOrderDelete extends BackendActiveRecord {
	const REASON_GOODS_NOT_PATCH_ORDER = 0;
	const REASON_ORDER_IS_NOT_EXIST = 1;
	/**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'stock_order_delete';
    }
    public function behaviors()
    {
        return BaseArrayHelper::merge(
            parent::behaviors(),
            [
                'timestamp' => [
                    'class' => 'yii\behaviors\TimestampBehavior',
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created_time', 'modified_time'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => 'modified_time',
                    ],
                ],
                'attributeStamp' => [
                      'class' => 'yii\behaviors\AttributeBehavior',
                      'attributes' => [
                          ActiveRecord::EVENT_BEFORE_INSERT => ['created_uid','modified_uid'],
                          ActiveRecord::EVENT_BEFORE_UPDATE => 'modified_uid',
                      ],
                      'value' => function () {
                          return Yii::$app->user->id;
                      },
                ],
           ]
        );
    }
    public static function getDeleteReasons(){
    	return [
    		self::REASON_GOODS_NOT_PATCH_ORDER =>'入库商品与订单不符',
    		self::REASON_ORDER_IS_NOT_EXIST => '订单商品不存在',
    	];
    }
}