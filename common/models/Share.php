<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Share extends ActiveRecord
{
    const STATUS_IS_NORMAL = 0;
    const STATUS_IS_NOT_NORMAL = 1;
	public function behaviors()
	{
		return [
			'timestamp' => [
				'class' => 'yii\behaviors\TimestampBehavior',
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => ['created', 'modified'],
					ActiveRecord::EVENT_BEFORE_UPDATE => 'modified',
				],
				'value' => function (){ return date("Y-m-d H:i:s");}
			],
		];
	}
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'share';
    }

    public static function updateShare($owner_id,$to_customer_id,$material_id,$storeroom_id){
        $result = static::find()->where(['owner_id'=>$owner_id,'to_customer_id'=>$to_customer_id,'material_id'=>$material_id,'storeroom_id'=>$storeroom_id,'status'=>self::STATUS_IS_NORMAL])->one();
        if(empty($result)){
            $model = new Static;
            $model->owner_id = $owner_id;
            $model->to_customer_id = $to_customer_id;
            $model->material_id = $material_id;
            $model->storeroom_id = $storeroom_id;
            $model->status = self::STATUS_IS_NORMAL;
            $model->created_uid = Yii::$app->user->id;
            if($model->save(false)){
                return true;
            }
        }
        
    }
}
