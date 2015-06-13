<?php
namespace customer\models;
use Yii;
use customer\components\CustomerActiveRecord;
use yii\helpers\BaseArrayHelper;

class Cart extends CustomerActiveRecord {
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'cart';
    }
    public function behaviors()
    {
        return BaseArrayHelper::merge(
            parent::behaviors(),
            [
                'timestamp' => [
                    'class' => 'yii\behaviors\TimestampBehavior',
                    'attributes' => [
                        CustomerActiveRecord::EVENT_BEFORE_INSERT => ['created', 'modified'],
                        CustomerActiveRecord::EVENT_BEFORE_UPDATE => 'modified',
                    ],
                    'value' => function (){ return date("Y-m-d H:i:s");}
                ],
           ]
        );
    }
    /**
     * table cart and storeroom  relation ship
     * @return [type] [description]
     */
    public function getStoreroom(){
        return $this->hasOne(Storeroom::className(),['id'=>'storeroom_id']);
    }
    /**
     * table cart and storeroom  relation ship
     * @return [type] [description]
     */
    public function getMaterial(){
        return $this->hasOne(Material::className(),['id'=>'material_id']);
    }
    /**
     * [getCartProductIdsByUid description]
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getCartProductIdsByUid($uid,$material_id,$storeroom_id){
        return static::find()->where(['uid'=>$uid,'material_id'=>$material_id,'storeroom_id'=>$storeroom_id])->one();
    }
    /**
     * [getCartsByUid description]
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getCartsByUid($uid){
        return Cart::find()->with(['storeroom','material'])->where(['uid'=>$uid])->all();
    }
    public function getStocks(){
        return Stock::getStockByUidAndStorageIdAndMaterialId($this->storeroom_id,$this->material_id);
    }
    public static function getCartsInfo($items){
        $ret = [];
        if(!empty($items)){
            foreach($items as $key=>$item){
                $ret[$key]['info'] = Cart::find()->with(['storeroom','material'])->where(['id'=>$item['cart_id']])->one();
                $ret[$key]['quantity'] = $item['quantity'];
                $ret[$key]['cart_id'] = $item['cart_id'];
            }
        }
        return $ret;        
    }
}