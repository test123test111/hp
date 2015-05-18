<?php

namespace oa\models;

use Yii;
use yii\db\ActiveRecord;

class Boardroom extends ActiveRecord {

    const STATUS_CAN_CHOOSE = 0;
    const STATUS_HAVE_CHOSEN = 1;

    public static function getDb() {
        return Yii::$app->get("oa");
    }

    public static function tableName() {
        return "boardroom";
    }

    public static function updateStatusById($id, $status) {
        $command = self::getDb()->createCommand();
        $result = $command->update(self::tableName(), ['status' => $status], 'id =', $id);
        return $result;
    }
    
    public static function fixAllStatus() {
        $time = time();
        $sql = "update boardroom set status = 0 where status = 1 and use_time > $time";
        $result = self::getDb()->createCommand($sql)->execute();
        return $result;
    }

    public function search($params = array()) {
        $query = self::find()->orderBy(['id' => SORT_DESC]);
        if (isset($params['name']) && $params['name'] != '') {
            $query->andWhere(['like', 'name', $params['name']]);
        }

        if (isset($params['type']) && $params['type'] != '') {
            $query->andWhere(['type' => $params['type']]);
        }

        $count = $query->count();
        $pages = new \yii\data\Pagination(['totalCount' => $count]);
        $data = $query->offset($pages->offset)->all();

        return [$data, $pages, $count];
    }

}
