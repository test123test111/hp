<?php

namespace oa\controllers;

use Yii;
use oa\models\Boardroom;
use oa\models\Boardroom\Record;
use yii\web\Controller;

class WboardroomController extends Controller {

    public $layout = false;
    public $enableCsrfValidation = false;

    public function actionMy($user_id) {
         Record::removeUnSign();

        $boardrooms = Boardroom::find()->orderBy(['id' => SORT_DESC])->asArray()->all();

        $records = Record::find()->where('user_id = :user_id', [':user_id' => $user_id])
                        ->andWhere('start_time > :start_time', [':start_time' => strtotime('-12 hour')])
                        ->asArray()->all();

        foreach ((array) $records as $key => $record) {
            if ($record['has_sign'] == 0 && $record['start_time'] < strtotime('+10 seconds')) {
                $records[$key]['status'] = 3; // 需要打卡
            } else {
                $records[$key]['status'] = 2;
            }
            $records[$key]['time_range'] = date('m-d H:i', $records[$key]['start_time'])
                    . '-' . date('H:i', $records[$key]['end_time']);
        }

        $data = array(
            'rooms' => $boardrooms,
            'records' => $records
        );
        echo json_encode($data);
        exit;
    }

    public function actionSign() {
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request;
            $user_id = (int) $request->post('user_id');
            $id = (int) $request->post('id');
            Record::signById((int) $id);
            $data = array(
                'errno' => 0,
                'msg' => ''
            );
            echo json_encode($data);
            exit;
        }
    }

    public function actionSignSucess() {
        return $this->render('signsucess');
    }

    public function actionRemove() {

        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request;
            $user_id = (int) $request->post('user_id');
            $id = (int) $request->post('id');
            $model = Record::find()->where([
                        'user_id' => $user_id,
                        'id' => $id
                    ])->one();
            $model->delete();
            $data = array(
                'errno' => 0,
                'msg' => ''
            );
            echo json_encode($data);
            exit;
        }
    }

    public function actionChoice($room_id) {

        $records = Record::find()->andWhere('room_id = :room_id', [':room_id' => (int) $room_id])
                 ->andWhere('start_time > :start_time', [':start_time' => time()])
                ->orderBy(['end_time' => SORT_ASC])
                ->asArray()->all();


        $day_range = array();
        foreach (range(0, 6) as $day) {
            $day_range[] = date("y-m-d", strtotime("+$day day"));
        }

        $time_range = array();
        foreach (range(10, 22) as $h) {
            $time_range[] = $h;
        }
        foreach ((array) $records as $key => $record) {
            $records[$key]['time_range'] = date('m-d H:i', $records[$key]['start_time'])
                    . '-' . date('H:i', $records[$key]['end_time']);
        }
        $data = array(
            'day_range' => $day_range,
            'time_range' => $time_range,
            'records' => $records,
        );
        echo json_encode($data);
        exit;
    }

    public function actionBooking() {
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request;
            $use_day = $request->post('room_id');
            $use_day = $request->post('day');
            $start_time = $request->post('start_time');
            $second = (int) $request->post('use_second');
            $start_datetime = $use_day . ' ' . $start_time;
            $end_datetime = $start_datetime . ':' . $second;
            
            $start_time = \DateTime::createFromFormat('y-m-d H', $start_datetime)->getTimestamp();
            $end_time = \DateTime::createFromFormat('y-m-d H:i', $end_datetime)->getTimestamp();
            $user_id = $request->post('user_id');
            $room_id = (int) $request->post('room_id');
            $roomInfo = Boardroom::find()->where(['id' => (int) $room_id])->one();
            $data = array(
                'end_time' => $end_time,
                'start_time' => $start_time,
                'user_id' => strip_tags($user_id),
                'room_id' => $room_id,
                'floor' => $roomInfo['floor'],        
                'room_name' => $roomInfo['name']
            );
           
            
            if ($start_time < time()) {
                echo json_encode(array('errno' => 1001, 'msg'=> '选择的开始时间必须大于现在时间'));exit;
            }
            
            if (Record::bookingExists($data)) {
                 echo json_encode(array('errno' => 1002, 'msg'=> '该时段已经被占用'));exit;
            }
             Record::saveBooking($data);
            echo json_encode(array('errno' => 0, 'msg'=> ''));exit;
        }
    }

}
