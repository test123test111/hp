<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
/**
 * Class stock
 * @package common\models
 */
class OrderNote extends ActiveRecord
{
	const TYPE_IS_USER = 0;
	const TYPE_IS_BUYER = 1;
    const TYPE_IS_MANAGER = 2;
	const TYPE_IS_FINANCE = 3;
    /**
     * set table name
     * @return string
     */
    public static function tableName()
    {
        return 'order_note';
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
           ]
        );
    }
    /**
     * get note list by order id 
     * @param  intval order_id
     * @return array
     */
    public static function getNoteListByOrderId($order_id){
    	return Static::find()->where(['order_id'=>$order_id])->orderBy(['created_time'=>SORT_DESC])->all();
    }
    /**
     * [getTypeName description]
     * @return [type] [description]
     */
    public function getTypeName(){
    	return $this->typeList()[$this->type];
    }
    /**
     * note type name array 
     * @return [type] [description]
     */
    public function typeList(){
    	return [
    		self::TYPE_IS_USER => '买家',
    		self::TYPE_IS_BUYER => '买手',
    		self::TYPE_IS_MANAGER => '后台',
            self::TYPE_IS_FINANCE =>'财务',
    	];
    }
    /**
     * [createNote description]
     * @param  [type] $order_id         [description]
     * @param  [type] $content          [description]
     * @param  [type] $type             [description]
     * @param  [type] $created_username [description]
     * @return [type]                   [description]
     */
    public static function createNote($order_id,$content,$type,$created_username,$created_uid){
    	$model = new Static;
    	$model->order_id = $order_id;
    	$model->type = $type;
    	$model->content = \yii\helpers\Html::encode($content);
    	$model->created_username = $created_username;
        $model->created_uid = $created_uid;
    	if($model->save()){
    		return $model;
    	}
    	return false;
    }
}