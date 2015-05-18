<?php
namespace operate\controllers;
use operate\models\search\TradeCartSearch;
use operate\models\search\UserVipSearch;
use operate\models\search\UserSearch;
use operate\models\TradeCart;
use operate\models\UserVip;
use Yii;
use yii\filters\AccessControl;
use backend\components\BackendController;
use operate\models\User;
use operate\models\Order;
class UservipController extends BackendController{
    public $layout = false;

    /*
     * vip用户列表
     */
    public function actionList(){
        $searchModel = new UserVipSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('uservip', [
            'dataProvider' => $dataProvider,
            'model' => $searchModel,
        ]);
    }

    /*
     * 选择要添加vip的用户
     */
    public function actionChose(){
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('userchose', [
            'dataProvider' => $dataProvider,
            'model' => $searchModel,
        ]);
    }
    /*
     *
     */
    public function actionAdd(){
        $model = new UserVip();

        if($model->load(Yii::$app->request->post())) {
            $r = $model->save();
            if ($r) {
                return $this->redirect("/uservip/list");
            }
        }
        $user = new User();
        $id = Yii::$app->request->get('id');
        $userInfo = $user->findOne($id);
        $orderPhone = $userInfo->userOrderPhone;
        $orderAddr = $userInfo->userOrderAddr;
        return $this->render('uservipadd',
            ['userinfo'=>$userInfo,'model'=>$model,'orderPhone'=>$orderPhone,'orderAddr'=>$orderAddr]
        );
    }

    public function actionEdit(){
        $model = new UserVip();
        $id = Yii::$app->request->get('id');
        $vipInfo = $model->findOne($id);
        if (!empty($_POST)) {
            if($vipInfo->load(Yii::$app->request->post()) && $vipInfo->save()){
                Yii::$app->session->setFlash('success', '修改成功！');
                return $this->redirect("/uservip/list");
            }

        }

        $user = new User();
        $userInfo = $user->findOne($vipInfo->user_id);
        $orderPhone = $userInfo->userOrderPhone;
        $orderAddr = $userInfo->userOrderAddr;
        return $this->render('uservipedit',
            ['userinfo'=>$userInfo,'model'=>$vipInfo,'orderPhone'=>$orderPhone,'orderAddr'=>$orderAddr,]
        );

    }

    /*
     * vip用户购物车列表
     */
    public function actionCart(){
        $searchModel = new TradeCartSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('tradecart', [
            'dataProvider' => $dataProvider,
            'model' => $searchModel,
        ]);
    }

    public function actionDetail(){
        list($count,$pages,$data) = Order::getOrderListData(Yii::$app->request->getQueryParams());
        $orderStatusList = Order::getOrderStatusList();

        $user_id = Yii::$app->request->get('user_id');
        //用户信息
        $vipInfo = UserVip::getVipUserInfo($user_id);
        //商品图片
        $orderImgs = [];
        $sku_meta = [];
        foreach($data  as $k => $d){
            if(isset($d->stock['imgs']) && !empty($d->stock['imgs'])){
                $orderImgs[$d->stock['id']] = json_decode($d->stock['imgs'],true);
                $sku_meta[$d->stock['id']] = json_decode($d->stock['sku_meta'],true);
            }
        }
        //总订单量
        $orderTotalNum = Order::getOrderNum(['user_id'=>$user_id]);
        //取当月订单
        $begin_time = strtotime(date('Y-m-01',time()));
        $orderTheMonthNum = Order::getOrderNum(['user_id'=>$user_id,'begin_time'=>$begin_time]);

        //取总成交额
        $orderTotalMoney = Order::getSuccessOrderMoney(['user_id'=>$user_id]);
        //当月成交额
        $orderTheMonthTotalMoney = Order::getSuccessOrderMoney(['user_id'=>$user_id,'begin_time'=>$begin_time]);

        //取购物车商品数量
        $tradeCartNums = count(TradeCart::getUserCartStocks($user_id));

        return $this->render('userviporder', [
            'results' => $data,
            'pages' => $pages,
            'params'=>Yii::$app->request->getQueryParams(),
            'count'=>$count,
            'orderStatusList' => $orderStatusList,
            'orderImgs' => $orderImgs,
            'sku_meta'=>$sku_meta,
            'vipInfo' => $vipInfo,
            'orderTotalNum' => is_null($orderTotalNum) ? 0 : $orderTotalNum,
            'orderTheMonthNum' => is_null($orderTheMonthNum) ? 0 :$orderTheMonthNum,
            'orderTotalMoney' => is_null($orderTotalMoney) ? 0 : $orderTotalMoney,
            'orderTheMonthTotalMoney' => is_null($orderTheMonthTotalMoney) ? 0 : $orderTheMonthTotalMoney,
            'tradeCartNums' => $tradeCartNums,
        ]);

    }

    /*
     * 放入购物车中的用户
     */
    public function actionCartuser(){

        $stock_id = Yii::$app->request->get('stock_id');
        $uids = TradeCart::getUserByCartStock($stock_id);
        $searchModel = new UserVipSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),$uids);
        return $this->render('cartvipuser', [
            'dataProvider' => $dataProvider,
            'model' => $searchModel,
        ]);
    }
    public function actionImport(){
        $startId = Yii::$app->request->get('startId');
        $endId = Yii::$app->request->get('endId');

        if(!isset($endId) || !$endId ||$endId <= $startId){
            $endId = $startId;
        }
        echo "导入用户id为".$startId."~".$endId;
        echo "<br>";

        $k=1;
        for($i=$startId;$i<=$endId;$i++) {

            $insertData = [];
            $user = new User();
            $id = $i;
            $userInfo = $user->findOne($id);

            if (!$userInfo || empty($userInfo)) {
                continue;
            }
            $orderPhone = $userInfo->userOrderPhone;
            //$orderAddr = $userInfo->userOrderAddr;
            $insertData['UserVip']['user_id'] = $userInfo->id;
            $insertData['UserVip']['name'] = $userInfo->name;
            $insertData['UserVip']['phone'] = isset($orderPhone[0]['cellphone']) && is_numeric($orderPhone[0]['cellphone']) ? $orderPhone[0]['cellphone'] : $userInfo->phone;
            $insertData['UserVip']['qq'] = $userInfo->qq;
            $insertData['UserVip']['weixin'] = $userInfo->weixin_id;
            $insertData['UserVip']['user_create_time'] = $userInfo->create_time;
            //echo strlen($insertData['phone']);
            $vipModel = new UserVip();
            if ($vipModel->load($insertData) && $r = $vipModel->save()) {
                echo "User id:" . $id . "导入成功" . "<br>";
                $k++;
            }

        }
        echo '共导入'.$k.'用户';
    }
    public function actionTest(){

        echo "<pre>";
        list($count,$pages,$data)=Order::getOrderListData(Yii::$app->request->getQueryParams());
        //var_dump($data);
        /*
        $orderImgs = [];
        $sku_meta = [];
        foreach($data  as $k => $d){
            if(isset($d->stock['imgs']) && !empty($d->stock['imgs'])){
                //var_dump(json_decode($d->stock['imgs'],true));
                $orderImgs[$d->stock['id']] = json_decode($d->stock['imgs'],true);
                $sku_meta[$d->stock['id']] = json_decode($d->stock['sku_meta'],true);
            }
        }
        var_dump($sku_meta);
        */
        //var_dump($data);

        $user_id = Yii::$app->request->get('user_id');

        $vipInfo = UserVip::getVipUserInfo($user_id);
        //var_dump($vipInfo);

        /*
        $orderNum = Order::getOrderNum(['user_id'=>$user_id]);
        var_dump($orderNum);
        //取当月订单
        $begin_time = strtotime(date('Y-m-01',time()));
        $orderTheNum = Order::getOrderNum(['user_id'=>$user_id,'begin_time'=>$begin_time]);
        echo date('Y-m-01',time());
        var_dump($orderTheNum);

        //取总成交额
        $orderTotalMoney = Order::getSuccessOrderMoney(['user_id'=>$user_id]);
        //当月成交额
        $orderTheMonthTotalMoney = Order::getSuccessOrderMoney(['user_id'=>$user_id,'begin_time'=>$begin_time]);
        var_dump($orderTotalMoney,$orderTheMonthTotalMoney);
        */
        /*
        $userStocks = TradeCart::getUserCartStocks($user_id);
        var_dump($userStocks);
        */
        var_dump(TradeCart::getUserByCartStock(67));
    }
}