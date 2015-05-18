<?php
namespace backend\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
use backend\components\BackendActiveRecord;
class BoxOrder extends BackendActiveRecord {
    const ORDER_IS_TO_USER = 1;
    
    const ORDER_IS_NOT_TO_USER = 0;
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'box_order';
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
     * boxorder and box relationship
     * @return [type] [description]
     */
    public function getBoxs(){
        return $this->hasOne(Box::className(),['id'=>'box_id']);
    }
    /**
     * model BoxOrder and Order relationship
     * @return [type] [description]
     */
    public function getOrders(){
        return $this->hasOne(Order::className(),['id'=>'order_id']);
    }
    /**
     * manager and box relationship
     * @return [type] [description]
     */
    public function getManagers(){
        return $this->hasOne(Manager::className(),['id'=>'created_uid']);
    }
    /**
     * update box order's status as to_user
     * @param [type] $box_id   [description]
     * @param [type] $order_id [description]
     */
    public static function UpdateBoxOrderStatus($box_id,$order_id){
        $boxorder = static::find()->where(['box_id'=>$box_id,'order_id'=>$order_id])->one();
        if(!empty($boxorder)){
            $boxorder->status = self::ORDER_IS_TO_USER;
            $boxorder->save();
        }
    }
}