<?php
namespace backend\models;

use backend\components\BackendActiveRecord;

class AssignMent extends BackendActiveRecord{

	const ROLE_NAME_IS_ADMIN = 'admin';
	public static function tableName(){
		return "auth_assignment";
	}

	/**
	 * get role by uid
	 * @param  [type] $uid [description]
	 * @return [type]      [description]
	 */
	public static function CheckUserIsAdmin($uid){
		if($uid == 1){
			return true;
		}
		$results = static::find()->where(['user_id'=>$uid])->all();
		if(empty($results)){
			return false;
		}
		foreach($results as $result){
			if($result->item_name == self::ROLE_NAME_IS_ADMIN){
				return true;
			}
		}
		return false;
		
	}
}