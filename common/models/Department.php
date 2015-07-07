<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Department extends ActiveRecord
{
    const IS_COMERCIAL = 1;
    const IS_CONSUMER = 2;
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
        return 'department';
    }
    /**
     * table category and table department relationship
     * @return [type] [description]
     */
    public function getCategories(){
    	return $this->hasMany(Category::className(),['department_id'=>'id']);
    }
    /**
     * 
     * @return [type] [description]
     */
    public function attributeLabels(){
        return [
            'name'=>'部门名称',
            'created'=>'创建时间',
        ];
    }
}
