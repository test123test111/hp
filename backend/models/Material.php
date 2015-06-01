<?php
namespace backend\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
use backend\components\BackendActiveRecord;

class Material extends BackendActiveRecord {
    public $upload;

    const PROPERTY_IS_PRESSIE = 0;
    const PROPERTY_IS_SAMPLE = 1;
    const PROPERTY_IS_POP = 2;
    const PROPERTY_IS_GIFT = 3;
    const PROPERTY_IS_CLOTH = 4;
    const PROPERTY_IS_MAKINGS = 5;

    const DATASOURCE_IS_IMPORT = 0;
    const DATASOURCE_IS_ADD = 1;
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'material';
    }

    /**
     * function_description
     *
     *
     * @return
     */
    public function rules() {
        return [
            [['name','property','package','owner_id','pn','datasource'],'required'],
            // ['code','unique'],
            [['desc','image','size','weight','stuff','expire','jiliang','code'],'safe']
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
     * [getCanUseProjects description]
     * @return [type] [description]
     */
    public function getCanUseProjects(){
        $rs = Project::find()->all();
        $arr = [];
        if($rs){
            foreach($rs as $key=>$v){
                $arr[$v['id']]=$v['name'];
            }

        }
        return $arr;
    }
    /**
     * [getCanUseStorerooms description]
     * @return [type] [description]
     */
    public function getCanUseOwners(){
        $rs = Owner::find()->all();
        $arr = [];
        if($rs){
            foreach($rs as $key=>$v){
                $arr[$v->id]=$v->english_name;
            }

        }
        return $arr;
    }
    /**
     * [getCanUseProperty description]
     * @return [type] [description]
     */
    public function getCanUseProperty(){
        return [
            self::PROPERTY_IS_SAMPLE =>'样机',
            self::PROPERTY_IS_POP=>'POSM',
            self::PROPERTY_IS_CLOTH => '耗材',
            self::PROPERTY_IS_PRESSIE => '资料',
        ];
    }
    /**
     * [getCanUseProperty description]
     * @return [type] [description]
     */
    public function getCanUseDataSource(){
        return [
            self::DATASOURCE_IS_ADD => '添加',
            self::DATASOURCE_IS_IMPORT =>'导入',
        ];
    }
    /**
     * function_description
     *
     *
     * @return
     */
    public function beforeSave($insert)
    {
        // process dependent category
        if (is_array($this->image)) {
            $this->image = serialize($this->image);
        }
        return parent::beforeSave($insert);
    }
    /**
     * function_description
     *
     *
     * @return
     */
    public function afterFind() {
        if (!is_array($this->image)) {
            @$this->image = unserialize($this->image);
        }
    }
    public function getProjects(){
        return $this->hasOne(Project::className(),['id'=>'project_id']);
    }
    public function getStocktotal(){
        return $this->hasOne(StockTotal::className(),['material_id'=>'id']);
    }
    public function getOwners(){
        return $this->hasOne(Owner::className(),['id'=>'owner_id']);
    }
    public function getStoreroome(){
        return $this->hasOne(Storeroom::className(),['id'=>'storeroom_id']);
    }
    /**
     * [getPropertyName description]
     * @return [type] [description]
     */
    public function getPropertyName(){
        return function ($model) {
            return $this->getCanUseProperty()[$model->property];
        };
    }
    /**
     * [getPropertyName description]
     * @return [type] [description]
     */
    public function getMyPropertyName(){
        return $this->getCanUseProperty()[$this->property];
    }
    /**
     * [getPropertyName description]
     * @return [type] [description]
     */
    public function getMyDataSourceName(){
        return $this->getCanUseDataSource()[$this->datasource];
    }
    /**
     * [getDataSourceName description]
     * @return [type] [description]
     */
    public function getDataSourceName(){
        return function ($model) {
            return $this->getCanUseDataSource()[$model->datasource];
        };
    }
    /**
     * [getCanUseProjects description]
     * @return [type] [description]
     */
    public function getCanUseOwner(){
        $rs = Owner::find()->all();
        $arr = [];
        if($rs){
            foreach($rs as $key=>$v){
                $arr[$v['id']]=$v['english_name'];
            }

        }
        return $arr;
    }
    public function attributeLabels(){
        return [
            'code'=>'物料编码',
            'name'=>'物料名称',
            'english_name'=>'英文名称',
            'owner_id'=>'所属人',
            'project_id'=>'所属项目',
            'desc'=>'物料描述',
            'image'=>'物料图片',
            'property'=>'类别',
            'expire'=>'使用期限',
            'channel'=>'产品线',
            'datasource'=>'数据来源',
            'size'=>'规格尺寸',
            'weight'=>'单位重量(g)',
            'stuff'=>'材料',
            'created'=>'添加时间',
            'created_uid'=>'创建人',
            'package'=>'包装规格',
            'jiliang'=>'计量规格',
        ];
    }
}