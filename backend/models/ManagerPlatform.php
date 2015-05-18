<?php
namespace backend\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\BaseArrayHelper;
use backend\components\BackendActiveRecord;
class ManagerPlatform extends BackendActiveRecord {
    const PLATFORM_CANGCHU = 'cangchu';
    const PLATFORM_OPERATE = 'operate';
    const PLATFORM_BOSS = 'boss';
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'manager_platform';
    }
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    BackendActiveRecord::EVENT_BEFORE_INSERT => ['created_time', 'modified_time'],
                    BackendActiveRecord::EVENT_BEFORE_UPDATE => 'modified_time',
                ],
            ],
        ];
    }
    /**
     * get user by app id
     * @param  string app id
     * @return intval manager id
     */
    public static function getUserByPlatformAndUid($app_id,$uid){
        return static::find()->where(['platform'=>$app_id,'manager_id'=>$uid])->one();
    }
    /**
     * update terms for object
     * @param  intval $object_id 
     * @param  array $terms 
     * @return boolean
     */
    public function updateManagerPlatform($manaegr_id,$platforms){

        
        // get current dependence
        $current = $this->getPlatformsByManager($manaegr_id);
        // calculate need to delete
        $to_del = array_diff($current, $platforms);
        // calculate need to insert
        $to_insert = array_diff($platforms, $current);

        // save to db
        if (!empty($to_del)) {
            $this->removeTerms($manaegr_id,$to_del);
        }
        if (!empty($to_insert)) {
            $this->addTerms($manaegr_id,$to_insert);
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
    protected function getPlatformsByManager($manager_id) {
        $query = new \yii\db\Query;
        $rows = $query->select('platform')
                     ->from($this->tableName())
                     ->where(array('and',
                             'manager_id=:manager_id',
                         ),
                         array(
                             ':manager_id'=>$manager_id,
                         ))
                     ->all(static::getDb());
        return array_map(function($a){return $a['platform'];},$rows);
    }
    /**
     * function_description
     *
     *
     * @return
     */
    protected function addTerms($model_id,$platforms) {
        if (empty($platforms)) {
            return true;
        }
        $sql = "INSERT INTO " . $this->tableName() . " (manager_id,platform) VALUES (:manager_id,:platform)";
        $cmd = static::getDb()->createCommand($sql);
        if (!is_array($platforms)) {
            $platforms = array($platforms);
        }
        $cmd->bindParam(":manager_id", $model_id);
        foreach ($platforms as $id) {
            if(empty($id)){
                continue;
            }
            $cmd->bindParam(":platform", $id);
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
    protected function removeTerms($manager_id,$platforms) {
        if (empty($platforms)) {
            return true;
        }
        $sql="DELETE FROM " . $this->tableName() .
        " WHERE manager_id=:manager_id ";
        $sql .= " AND platform in ('".implode("','",$platforms)."')";
        if (!is_array($platforms)) {
            $platforms = array($platforms);
        }
        $cmd = static::getDb()->createCommand($sql);
        $cmd->bindParam(":manager_id", $manager_id);
        return $cmd->execute();
    }
}