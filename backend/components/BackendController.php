<?php

namespace backend\components;

use Yii;
use yii\web\Controller;

class BackendController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \backend\components\filters\AccessControl::className(),
            ],
        ];
    }

    /**
     * send json encoded response to user
     * @return string json encoded response string ,with shield "for (;;);{body}end;;;"
     */
    public function sendResponse(array $result) {
    	$result['timestamp'] = time();
    	return @json_encode($result);
    }
}