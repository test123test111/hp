<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
/**
 * Class User
 * @package common\models
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $role
 * @property integer $status
 * @property integer $create_time
 * @property integer $update_time
 */
class News extends ActiveRecord
{

    const STATUS_DELETED = 1;
    const STATUS_NORMAL = 0;

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
    public static function tableName(){
        return "news";
    }
    public function rules()
    {
        return [
            [['title','content'],'required'],
        ];
    }

    public function attributeLabels(){
        return [
            'title'=>'标题',
            'content'=>'内容',
            'created'=>'创建时间',
        ];
    }
}
