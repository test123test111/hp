<?php
namespace backend\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
use backend\components\BackendActiveRecord;
class WaitOutput extends BackendActiveRecord {
	/**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'wait_output';
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
    /**
     * table wait_out_put and table order relationship
     * @return [type] [description]
     */
    public function getOrders(){
        return $this->hasOne(Order::className(),['id'=>'order_id']);
    }
}