<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use backend\models\Material;
use backend\models\Owner;
use backend\models\StockTotal;
use backend\models\Storeroom;
use backend\models\Stock;
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
    public function getStocks(){
        return $this->hasOne(Stock::className(),['material_id'=>'material_id','storeroom_id'=>'storeroom_id'])->orderBy(['stock_time'=>SORT_ASC]);
    }
    public function getStorerooms(){
        return $this->hasOne(Storeroom::className(),['id'=>'storeroom_id']);
    }
    public static function updateShare($owner_id,$to_customer_id,$material_id,$storeroom_id){
        $owner = Owner::findOne($owner_id);
        $cat_id = 0;
        if($owner->categor != 0){
            $category = Category::findOne($owner->category);
            $cat_id = $category->id;
        }
        $material = Material::findOne($material_id);
        $bigOwner = Owner::getBigOwnerByUid($owner_id);
        if($bigOwner){
            $big_uid = $bigOwner->id;
            $big_result = static::find()->where(['owner_id'=>$owner_id,'to_customer_id'=>$big_uid,'material_id'=>$material_id,'storeroom_id'=>$storeroom_id,'status'=>self::STATUS_IS_NORMAL])->one();
            if(empty($result)){
                $model = new Static;
                $model->owner_id = $owner_id;
                $model->to_customer_id = $big_uid;
                $model->material_id = $material_id;
                $model->storeroom_id = $storeroom_id;
                $model->status = self::STATUS_IS_NORMAL;
                $model->created_uid = Yii::$app->user->id;
                $model->category = $cat_id;
                $model->property = $material->property;
                $model->save(false);
            }
        }
        $result = static::find()->where(['owner_id'=>$owner_id,'to_customer_id'=>$to_customer_id,'material_id'=>$material_id,'storeroom_id'=>$storeroom_id,'status'=>self::STATUS_IS_NORMAL])->one();
        if(empty($result)){
            $model = new Static;
            $model->owner_id = $owner_id;
            $model->to_customer_id = $to_customer_id;
            $model->material_id = $material_id;
            $model->storeroom_id = $storeroom_id;
            $model->status = self::STATUS_IS_NORMAL;
            $model->created_uid = Yii::$app->user->id;
            $model->category = $cat_id;
            $model->property = $material->property;
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
     * [updateShare description]
     * @return [type] [description]
     */
    public static function updateShareByOwnerId($material_id,$storeroom_id,$owner_id,$to_uids){
        $bigOwner = Owner::getBigOwnerByUid($owner_id);
        if(!empty($bigOwner)){
            $big_owner_uid = $bigOwner->id;
            unset($to_uids['big_owner_uid']);
        }
        if(empty($to_uids)){
            return static::removeShare($material_id,$storeroom_id,$owner_id,$to_uids);
        }
        $current = Share::find()->select('to_customer_id')->where(['material_id'=>$material_id,'owner_id'=>$owner_id,'storeroom_id'=>$storeroom_id,'status'=>self::STATUS_IS_NORMAL])->andWhere(['<>','to_customer_id',$owner_id])->column();
        // calculate need to delete
        $to_del = array_diff($current, $to_uids);
        // calculate need to insert
        $to_insert = array_diff($to_uids, $current);
        // save to db
        if (!empty($to_del)) {
            static::removeShare($material_id,$storeroom_id,$owner_id,$to_del);
        }
        if (!empty($to_insert)) {
            static::addShare($material_id,$storeroom_id,$owner_id,$to_insert);
        }
        return true;
    }
    /**
     * function_description
     *
     *
     * @return
     */
    protected static function addShare($material_id,$storeroom_id,$owner_id,$to_customer_ids) {
        if (empty($to_customer_ids)) {
            return true;
        }
        foreach($to_customer_ids as $to_customer_id){
            $share = static::find()->where(['material_id'=>$material_id,'storeroom_id'=>$storeroom_id,'owner_id'=>$owner_id,'to_customer_id'=>$to_customer_id])->one();
            if(!empty($share)){
                $share->status = self::STATUS_IS_NORMAL;
                $share->update();
            }else{
                $model = new static;
                $model->material_id = $material_id;
                $model->storeroom_id = $storeroom_id;
                $model->owner_id = $owner_id;
                $model->to_customer_id = $to_customer_id;
                $model->created_uid = $owner_id;
                $model->modified_uid = $owner_id;
                $model->save();
            }
        }
        return true;
    }
    /**
     * function_description
     *
     * @param $model_type:
     * @param $model_id:
     * @param $dep_type:
     * @param $dep_ids:
     *
     * @return
     */
    protected static function removeShare($material_id,$storeroom_id,$owner_id,$to_customer_ids) {
        if (empty($to_customer_ids)) {
            return true;
        }
        return static::updateAll(['status'=>self::STATUS_IS_NOT_NORMAL],['material_id'=>$material_id,'storeroom_id'=>$storeroom_id,'owner_id'=>$owner_id,'to_customer_id'=>$to_customer_ids]);
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
