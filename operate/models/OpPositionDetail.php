<?php
namespace operate\models;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;

class OpPositionDetail extends ActiveRecord {

    const TYPE_GOODS = 0;
    const TYPE_BUYER = 1;
    const TYPE_LIVE = 2;
    const TYPE_URL = 4;
    const TYPE_CATEGORY = 5;
    const TYPE_SUBJECT = 6;

    public static function tableName(){
        return "op_position_detail";
    }
    /**
     * attributes rules
     * @return [type] [description]
     */
    public function rules()
    {
        return [
          [['position_id'],'required'],
          [['name','column_name','type','need_image','need_text','text_content','desc'],'safe'],
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
    /**
     * table op_position_detail and op_postion relationship
     * @return [type] [description]
     */
    public function getPostion(){
    	 return $this->hasOne(OpPosition::className(),['id'=>'postion_id']);
    }
   /**
     * get detail object type
     */
    public function getDetailType(){
        return $this->getCanUseTypes()[$this->type];
    }
    /**
     * position detail type list array
     * @return [type] [description]
     */
    public function getCanUseTypes(){
        return [
            self::TYPE_URL => '网址',
            self::TYPE_LIVE => '直播',
            self::TYPE_BUYER => '买手',
            self::TYPE_GOODS => '商品',
            self::TYPE_CATEGORY => '分类',
            self::TYPE_SUBJECT => '专题',
        ];
    }
    /**
     * format post data 
     * return ['type'=>[1,2,3]]
     * @param  [type] $postData [description]
     * @return [type]           [description]
     */
    public function formatPostDataByType($postData){
        if(empty($postData)){
            return [];
        }
        $ret = [];
        foreach($postData as $data){
            if(isset($ret[$data['type']])){
                array_push($ret[$data['type']],$data['value']);
            }else{
                $ret[$data['type']] = [$data['value']];
            }
        }
        return $ret;
    }
    /**
     * save position details 
     * data is need insert into table,then insert
     * data is need remove,then remove
     * @param  array postdata
     * @return [type]           [description]
     */
    public function savePositionDetail($postdata){
        foreach($postdata as $key=>$data){
            if(!isset($data['image_path'])){
                $data['image_path'] = '';
            }
            if(!isset($data['text_content'])){
                $data['text_content'] = '';
            }
            // if($data['type'] == self::TYPE_URL){
            //     $data['value'] = 'http://'.$data['value'];
            // }
            static::updateAll(['type'=>$data['type'],'value'=>$data['value'],'image_path'=>$data['image_path'],'text_content'=>$data['text_content'],'updated_time'=>time()],['id'=>$key]);
        }
        return true;
    }
    /**
     * update terms for object
     * @param  intval $object_id 
     * @param  array $terms 
     * @return boolean
     */
    public function updatePositionDetails($position_id,$type,$details){
        // get current dependence
        $current = $this->getDetailByPositionId($position_id,$type);
        // calculate need to delete
        $to_del = array_diff($current, $details);
        // calculate need to insert
        $to_insert = array_diff($details, $current);

        // save to db
        if (!empty($to_del)) {
            $this->removeTerms($position_id,$type,$to_del);
        }
        if (!empty($to_insert)) {
            $this->addTerms($position_id,$type,$to_insert);
        }
        return true;
    }
    /**
     * get terms of page id 
     *
     * @param $page_id:
     *
     * @return
     */
    protected function getDetailByPositionId($position_id,$type) {
        $query = new \yii\db\Query;
        $rows = $query->select(['type','value'])
                     ->from($this->tableName())
                     ->where(array('and',
                             'position_id=:position_id',
                             'type=:type',
                         ),
                         array(
                             ':position_id'=>$position_id,
                             ':type'=>$type,
                         ))
                     ->all(static::getDb());
        return array_map(function($a){return $a['value'];},$rows);
    }
    /**
     * function_description
     *
     *
     * @return
     */
    protected function addTerms($model_id,$type,$details) {
        if (empty($details)) {
            return true;
        }
        $sql = "INSERT INTO " . $this->tableName() . " (position_id,type,:value) VALUES (:position_id,:type,:value)";
        $cmd = static::getDb()->createCommand($sql);
        if (!is_array($details)) {
            $details = array($details);
        }
        $cmd->bindParam(":position_id", $model_id);
        $cmd->bindParam(":type", $type);
        foreach ($details as $detail) {
            if(empty($detail)){
                continue;
            }
            $cmd->bindParam(":value", $detail);
            $cmd->execute();
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
    protected function removeTerms($type,$type,$details) {
        if (empty($details)) {
            return true;
        }
        $sql="DELETE FROM " . $this->tableName() .
        " WHERE position_id=:position_id AND type=:type";
        $sql .= " AND platform in ('".implode("','",$details)."')";
        if (!is_array($details)) {
            $details = array($details);
        }
        $cmd = static::getDb()->createCommand($sql);
        $cmd->bindParam(":position_id", $position_id);
        $cmd->bindParam(":type", $type);
        return $cmd->execute();
    }
}