<?php
namespace backend\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
use common\models\HpCity;

class Storeroom extends ActiveRecord {
    const STOREROOM_LEVEL_IS_CENTER = 1;
    const STOREROOM_LEVEL_IS_PLATFORM = 2;
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'storeroom';
    }

    /**
     * function_description
     *
     *
     * @return
     */
    public function rules() {
        return [
            [['name','level','province','city'],'required'],
            [['address','contact','phone','district'],'safe'],
        ];
    }
    public function behaviors()
    {
        return BaseArrayHelper::merge(
            parent::behaviors(),
            [
                'timestamp' => [
                    'class' => 'yii\behaviors\TimestampBehavior',
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created', 'modified'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => 'modified',
                    ],
                    'value' => function (){ return date("Y-m-d H:i:s");}
                ],
           ]
        );
    }
    public function getCitydata(){
        return $this->hasOne(HpCity::className(),['id'=>'city']);
    }
    public function updateAttrs($attributes){
        $attrs = array();
        if (!empty($attributes['name']) && $attributes['name'] != $this->name) {
            $attrs[] = 'name';
            $this->name = $attributes['name'];
        }
        if (!empty($attributes['level']) && $attributes['level'] != $this->level) {
            $attrs[] = 'level';
            $this->level = $attributes['level'];
        }
        if (!empty($attributes['address']) && $attributes['address'] != $this->address) {
            $attrs[] = 'address';
            $this->address = $attributes['address'];
        }
        if (!empty($attributes['contact']) && $attributes['contact'] != $this->contact) {
            $attrs[] = 'contact';
            $this->contact = $attributes['contact'];
        }
        if (!empty($attributes['phone']) && $attributes['phone'] != $this->phone) {
            $attrs[] = 'phone';
            $this->phone = $attributes['phone'];
        }
        if ($this->validate($attrs)) {
            return $this->save(false);
        } else {
            return false;
        }
    }
    /**
     * [getDefaultCategory description]
     * @return [type] [description]
     */
    public function getDefaultCity(){
        return \yii\helpers\ArrayHelper::map(HpCity::find()->where(['pid' => $this->province])->all(),'id','name');
    }
    /**
     * [getDefaultDistrict description]
     * @return [type] [description]
     */
    public function getDefaultDistrict(){
        return \yii\helpers\ArrayHelper::map(HpCity::find()->where(['pid' => $this->city])->all(),'id','name');
    }
    /**
     * [getCanChoseProvince description]
     * @return [type] [description]
     */
    public function getCanChoseProvince(){
        $results = HpCity::find()->where(['pid'=>0])->all();
        $ret = [];
        foreach($results as $result){
            $ret[$result->id] = $result->name;
        }
        return $ret;
    }
    public function attributeLabels(){
        return [
            'name'=>'仓库名',
            'address'=>'仓库地址',
            'level'=>'仓库等级',
            'contact'=>'联系人',
            'phone'=>'联系电话',
            'province'=>'所在省份',
            'city'=>'所在城市',
            'district'=>'所在区县',
        ];
    }
}