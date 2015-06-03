<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use backend\models\Material;
use backend\models\Owner;
use backend\models\StockTotal;
use backend\models\Storeroom;
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

    public function getMaterials(){
        return $this->hasOne(Material::className(),['id'=>'material_id']);
    }
    public function getOwners(){
        return $this->hasOne(Owner::className(),['id'=>'owner_id']);
    }
    public function getStockTotals(){
        return $this->hasOne(StockTotal::className(),['material_id'=>'material_id','storeroom_id'=>'storeroom_id']);
    }
    public function getStorerooms(){
        return $this->hasOne(Storeroom::className(),['id'=>'storeroom_id']);
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
    public static function updateMaterial($uid,$category){
        $owner_ids = Owner::find()->select('id')->where(['category'=>$category])->column();
        foreach($owner_ids as $owner_id){
            $results = StockTotal::find()->where(['owner_id'=>$owner_id])->all();
            foreach($results as $result){
                $share = static::find()->where(['owner_id'=>$owner_id,'to_customer_id'=>$uid,'material_id'=>$result->material_id,'status'=>self::STATUS_IS_NORMAL])->one();
                if(empty($share)){
                    $model = new static;
                    $model->material_id = $result->material_id;
                    $model->storeroom_id = $result->storeroom_id;
                    $model->owner_id = $result->owner_id;
                    $model->to_customer_id = $uid;
                    $model->created = date('Y-m-d H:i:s');
                    $model->modified = date('Y-m-d H:i:s');
                    $model->save(false);
                }
            }
        }
    }
    /**
     * [recove description]
     * @param  [type] $to_customer_id [description]
     * @return [type]                 [description]
     */
    public static function recove($to_customer_id){
        return static::updateAll(['status'=>self::STATUS_IS_NOT_NORMAL],['to_customer_id'=>$to_customer_id]);
    }
}
