<?php
namespace backend\models;

use backend\components\BackendActiveRecord;

class Assign extends BackendActiveRecord{
	public static function tableName(){
		return "auth_item_child";
	}
}