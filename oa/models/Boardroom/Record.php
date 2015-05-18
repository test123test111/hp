<?php

namespace oa\models\Boardroom;

use Yii;
use yii\db\ActiveRecord;

class Record extends ActiveRecord {

    public static function getDb() {
        return Yii::$app->get("oa");
    }

    public static function tableName() {
        return "boardroom_record";
    }

    public static function bookingExists($data, $check_sign = null) {
        extract($data);
        $sql = "SELECT count(id) as count FROM boardroom_record WHERE 1"
                . " AND start_time >= $start_time AND end_time <= $end_time"
                . " AND room_id = $room_id";

        $result = self::getDb()->createCommand($sql)->queryOne();
    
        if ($result['count'] == 0) {
             return false;
        } else {
            return true;
        }
    }

    
    public static function removeUnSign() {
        $time= strtotime("+10 seconds");
        $end_time = strtotime('+2 hours');
        $sql = "DELETE FROM boardroom_record  WHERE 1"
                . " AND start_time > $time AND end_time < $end_time"
                . " AND has_sign = 0";
        $result = self::getDb()->createCommand($sql)->execute();
        return $result;
    }

    
    public static function saveBooking($data) {
        extract($data);
        $builder = new \Jf\Db\Builder();
        $sql = $builder->insert(self::tableName(), $data)->assemble();
 
        self::getDb()->createCommand($sql)->execute();
        
        return self::getDb()->getLastInsertID();
    }
    
    public static function signById($id) {
        $sql = "update boardroom_record set has_sign = 1 where id = $id";
        $result = self::getDb()->createCommand($sql)->execute();
        return $result;
    }

}
