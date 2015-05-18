<?php
namespace operate\models;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
use backend\models\Manager;

class OpPosition extends ActiveRecord {

	const STATUS_ON_LINE = 1;
	const STATUS_OFF_LINE = 0;

	const TYPE_H5 = 0;
	const TYPE_APP_BUYER = 1;
	const TYPE_APP_USER = 2;
    const TYPE_GOODS_ITEM = 3;

    public static function tableName(){
        return "op_position";
    }

    public function behaviors()
    {
        return BaseArrayHelper::merge(
            parent::behaviors(),
            [
                'timestamp' => [
                    'class' => 'yii\behaviors\TimestampBehavior',
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created_time', 'updated_time'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_time',
                    ],
                ],
                'attributeStamp' => [
                      'class' => 'yii\behaviors\AttributeBehavior',
                      'attributes' => [
                          ActiveRecord::EVENT_BEFORE_INSERT => ['created_uid','updated_uid'],
                          ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_uid',
                      ],
                      'value' => function () {
                          return Yii::$app->user->id;
                      },
                ],
           ]
        );
    }

    public function rules()
    {
        return [
        	[['name'],'required'],
        	[['desc','status','type','image'],'safe'],
        ];
    }
    /**
     * save position and position detail 
     * @param  [type] $positionDetail [description]
     * @return [type]                 [description]
     */
    public function savePosition($positionDetail){
        $db = self::getDb();
        $transaction = $db->beginTransaction();
        try {
            $this->save();
            $this->saveDetails($positionDetail);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
    /**
     * table op_position and op_position_detail relationship
     * @return [type] [description]
     */
    public function getDetails(){
    	 return $this->hasMany(OpPositionDetail::className(),['position_id'=>'id']);
    }
    /**
     * table op_position and table manager relationship by created_id
     * @return [type] [description]
     */
    public function getManager(){
        return $this->hasOne(Manager::className(),['id'=>'created_uid']);
    }
    /**
     * save details 
     * @return [type] [description]
     */
    public function saveDetails($postData){
        if(!empty($postData)){
          	foreach($postData as $data){
            		for($i=1;$i<=$data['num'];$i++){
              			$model = new OpPositionDetail;
              			// unset($data['num']);
              			$data['position_id'] = $this->id;
              			$model->setAttributes($data);
              			$model->save(false);
            		}
          	}
            return true;
        }
    }
    /**
     * get position object status
     */
    public function getPositionStatus(){
        return function ($model) {
            return $this->getCanUseStatus()[$model->status];
        };
    }
    /**
     * position status list array
     * @return [type] [description]
     */
    public function getCanUseStatus(){
        return [
            self::STATUS_ON_LINE=>'上线',
            self::STATUS_OFF_LINE=>'已下线',
        ];
    }
    /**
     * get position object type
     */
    public function getPositionType(){
        return $this->getCanUseTypes()[$this->type];
    }
    public function getTypes(){
        return function ($model) {
            return $this->getCanUseTypes()[$model->type];
        };
    }
    /**
     * position type list array
     * @return [type] [description]
     */
    public function getCanUseTypes(){
        return [
            self::TYPE_APP_USER=>'APP买家版',
            self::TYPE_APP_BUYER=>'APP买⼿版',
            self::TYPE_H5=>'H5活动',
            self::TYPE_GOODS_ITEM=>'商品类目'
        ];
    }
    /**
     * 
     * @return [type] [description]
     */
    public function attributeLabels(){
        return [
            'name'=>'名称',
            'desc'=>'介绍',
            'status'=>'状态',
            'type'=>'分类',
            'created_time'=>'创建时间',
            'platform'=>'平台',
        ];
    }
    /**
     * offline a position
     * @return [type] [description]
     */
    public function offline(){
        $this->status = self::STATUS_OFF_LINE;
        if($this->update(false)){
            return true;
        }
        return false;
    }
    /**
     * online 
     * @return [type] [description]
     */
    public function online(){
        $this->status = self::STATUS_ON_LINE;
        if($this->update(false)){
            return true;
        }
        return false;
    }
    /**
     * get position detail group by name 
     * @return [type] [description]
     */
    public function getPositionDetails(){
        $ret = [];
        if(isset($this->details)){
            foreach($this->details as $key=>$detail){
                $ret[$detail->name][$key] = $detail;
            }
        }
        return $ret;
    }
}