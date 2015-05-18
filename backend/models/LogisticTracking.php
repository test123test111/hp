<?php
namespace backend\models;
use Yii;
use yii\db\ActiveRecord;
class LogisticTracking extends ActiveRecord {
    /**
     * function_description
     *
     *
     * @return
     */
    public static function tableName() {
        return 'logistic_tracking';
    }

}