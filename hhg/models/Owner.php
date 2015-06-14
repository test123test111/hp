<?php
namespace hhg\models;

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
class Owner extends ActiveRecord
{
    
    public static function tableName(){
        return "owner";
    }
    public static function getCreateUsers(){
    	$results = static::find()->all();
        $ret = [];
        foreach($results as $result){
            $array['text'] = $result->english_name;
            $array['id'] = $result->id;
            array_push($ret,$array);
        }
        return json_encode($ret);
    }
}	
