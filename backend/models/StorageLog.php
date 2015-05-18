<?php
namespace backend\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
use backend\components\BackendActiveRecord;
class StorageLog extends BackendActiveRecord {
	 /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'storage_log';
    }
    public function behaviors()
    {
        return BaseArrayHelper::merge(
            parent::behaviors(),
            [
                'timestamp' => [
                    'class' => 'yii\behaviors\TimestampBehavior',
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created_time'],
                    ],
                ],
                'attributeStamp' => [
                      'class' => 'yii\behaviors\AttributeBehavior',
                      'attributes' => [
                          ActiveRecord::EVENT_BEFORE_INSERT => ['created_uid'],
                      ],
                      'value' => function () {
                          return Yii::$app->user->id;
                      },
                ],
           ]
        );
    }
    /**
     * [saveLog description]
     * @param  [type] $box_id       [description]
     * @param  [type] $order_id     [description]
     * @param  [type] $action       [description]
     * @param  [type] $after_status [description]
     * @param  string $info         [description]
     * @return [type]               [description]
     */
    public static function saveLog($box_id,$order_id,$action,$after_status,$info = ''){
        $model = new Static;
        $model->box_id = $box_id;
        $model->order_id = $order_id;
        $model->action = $action;
        $model->after_status = $after_status;
        $model->info = $info;
        return $model->save();
    }
}