<?php
namespace backend\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
use backend\components\BackendActiveRecord;
class Printer extends BackendActiveRecord {
	const STATUS_IS_RIGHT = 0;
	const STATUS_IS_WRONG = 1;

	const TYPE_IS_ORDER = 0;
	const TYPE_IS_BOX = 1;
	/**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'printer';
    }

    /**
     * function_description
     *
     *
     * @return
     */
    public function rules() {
        return [
            [['type'],'required'],
            [['desc','status'],'safe'],
        ];
    }
    /**
     * get printer object status
     */
    public function getPrinterTypes(){
        return function ($model) {
            return $this->getCanUseTypes()[$model->type];
        };
    }
    /**
     * printer types list array
     * @return [type] [description]
     */
    public function getCanUseTypes(){
        return [
            self::TYPE_IS_ORDER=>'订单',
            self::TYPE_IS_BOX=>'箱子',
        ];
    }
    /**
     * printer status list
     * @return [type] [description]
     */
    public function getCanUseStatus(){
		return [
            self::STATUS_IS_RIGHT=>'正常',
            self::STATUS_IS_WRONG=>'禁用',
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
     * get printer id by type and user id
     * @param  int type
     * @param  int uid
     * @return int printer id
     */
    public static function getPrinterIdByTypeAndUser($type,$uid){
        $printer_users = PrinterUser::find()->where(['user_id'=>$uid,'type'=>$type])->one();
        if(empty($printer_users)){
            return 0;
        }
        return $printer_users->printer_id;
    }   
    /**
     * 
     * @return [type] [description]
     */
    public function attributeLabels(){
        return [
            'type'=>'类型',
            'desc'=>'介绍',
            'created_time'=>'创建时间',
            'status'=>'状态',
        ];
    }
}