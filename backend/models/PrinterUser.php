<?php
namespace backend\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
use backend\components\BackendActiveRecord;
class PrinterUser extends BackendActiveRecord {
    const STATUS_IS_RIGHT = 0;
    const STATUS_IS_WRONG = 1;
	/**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'printer_user';
    }

    /**
     * function_description
     *
     *
     * @return
     */
    public function rules() {
        return [
            [['user_id','printer_id'],'required'],
            ['status','safe'],
            ['type','required'],
            ['user_id','checkUser']
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
     * Validates the user and printer id.
     * This method serves as the inline validation for password.
     */
    public function checkUser()
    {
        $result = static::find()->where(['user_id'=>$this->user_id,'printer_id'=>$this->printer_id,'type'=>$this->type])->one();
        if(!empty($result)){
            $this->addError('status', '请勿重复创建.');
        }
    }
    /**
     * 
     * @return [type] [description]
     */
    public function attributeLabels(){
        return [
            'type'=>'类型',
            'user_id'=>'用户',
            'created_time'=>'创建时间',
            'status'=>'状态',
        ];
    }
    /**
     * printer_user and manager relationship
     * @return [type] [description]
     */
    public function getManagers(){
        return $this->hasOne(Manager::className(),['id'=>'user_id']);
    }
    /**
     * printer_user and printer relationship
     * @return [type] [description]
     */
    public function getPrinters(){
        return $this->hasOne(Printer::className(),['id'=>'printer_id']);
    }
    /**
     * before save need delete the same type and same user 
     * @return [type] [description]
     */
    public function beforeSave($insert){
        if ( parent::beforeSave($insert) ) {
            static::deleteAll(['type'=>$this->type,'user_id'=>$this->user_id]);
            return true;
        }
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
    /**
     * get can use users
     * @return [type] [description]
     */
    public function getCanUseUsers(){
        $rs = Manager::find()->batch();
        $arr = [];
        foreach($rs as $v){
            foreach($v as $v1){
                $arr[$v1['id']]=$v1['username'];
            }
        }
        return $arr;
    }
}