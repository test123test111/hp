<?php
/**
 * vip用户数据统计脚本
 * User: wuheping
 * Date: 15/5/11
 * Time: 上午10:59
 */
namespace console\controllers;
use yii;
use yii\console\Controller;
use operate\models\UserVip;
class UservipController extends Controller {

    /*
     * 统计脚本，加入crontab定时执行
     * 命令：./yii uservip/count
     * 统计指定用户：./yii uservip/count 3
     */
    public function actionCount($userId=NULL)
    {
        if(!$userId) {
            $query = UserVip::find();
            $vipUsers = $query->asArray()->all();
        }else{
            $vipUsers[]['user_id'] = $userId;
        }
        foreach ($vipUsers as $user) {
            //查找最后一单时间
            $model = UserVip::findOne(['user_id' => $user['user_id']]);
            $lastOrder = $model->lastOrder;
            $lastOrderTime = $lastOrder['create_time'];
            $totalOrderNum = $model->orderCount;
            $totalOrderMoney = $model->orderSum;
            $model->last_order_time = $lastOrderTime;
            $model->total_order_num = !is_null($totalOrderNum) ? $totalOrderNum : 0;
            $model->total_order_money = !is_null($totalOrderMoney) ? $totalOrderMoney : 0;
            if($model->save()){
                echo $user['user_id'].":更新成功\n";
            }else{
                echo $user['user_id'].":更新失败\n";
            }
        }
    }
}