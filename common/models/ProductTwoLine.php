<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class ProductTwoLine extends ActiveRecord
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
        return 'product_two_line';
    }
    /**
     * 
     * @return [type] [description]
     */
    public function attributeLabels(){
        return [
            'name'=>'二级产品线名称',
            'created'=>'创建时间',
        ];
    }
}
