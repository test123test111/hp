<?php
namespace common\components;
use yii\rbac\DBManager as DM;
use yii\rbac\Role;
use yii\rbac\Permission;

class DBManager extends DM
{

	/**
     * @inheritdoc
     */
    public function createRole($name)
    {
        $role = new Role;
        $role->name = $name;
        $role->platform = Yii::$app->id;
        return $role;
    }
    /**
     * @inheritdoc
     */
    public function createPermission($name)
    {
        $permission = new Permission();
        $permission->name = $name;
        $permission->platform = Yii::$app->id;
        return $permission;
    }
}