<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Category extends ActiveRecord
{
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
        return 'category';
    }
    /**
     * save category
     * @param  [type] $categories [description]
     * @return [type]             [description]
     */
    public function saveCategory($category){
    	$model = new Static;
    	$model->department_id = $this->id;
    	$model->name = $category['name'];
    }
    /**
     * 
     * @return [type] [description]
     */
    public function attributeLabels(){
        return [
            'name'=>'分组名称',
            'created'=>'创建时间',
        ];
    }
}
