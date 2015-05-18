<?php
namespace operate\models;
use backend\models\Order as ord;
use backend\models\Stock;
use backend\models\StockAmount;
use common\models\Buyer;
use common\models\Coupon;
use common\models\LiveStock;
use Yii;

class Order extends ord {

    /*
     * 取用户订单列表
     */
    public static function getOrderListData($params){
        $query = Order::find();
        if(isset($params['user_id']) && !empty($params['user_id'])) {
            $query->andWhere(['user_id' => $params['user_id']]);
        }
        if(isset($params['status']) && !empty($params['status'])){
            $status = $params['status'];
            if($status == 'canceled'){
                $status = ['canceled','fail'];
            }
            if($status == 'full_refund'){
                $status = ['full_refund','refund'];
            }

            $query->andWhere(['status'=>$status]);
        }
        $count = $query->select('id')->count();
        $pages = new \yii\data\Pagination(['totalCount' => $count,'defaultPageSize'=>10]);
        $query->select('*')
            ->offset($pages->offset)
            ->limit(10)
            ->orderby(['sum_price'=>SORT_DESC])
            ->with(['buyer','stock','coupon','stockLive']);
        ;
        $data = $query->all();
        return [$count,$pages,$data];
    }

    /*
     * 取用户订单数
     */
    public static function getOrderNum($params){
        $query = Order::find();
        if(isset($params['user_id']) && !empty($params['user_id'])) {
            $query->andWhere(['user_id' => $params['user_id']]);
        }
        if(isset($params['begin_time']) && !empty($params['begin_time'])){
            $query->andWhere(['>=','create_time',$params['begin_time']]);
        }
        if(isset($params['end_time']) && !empty($params['end_time'])){
            $query->andWhere(['<=','end_time',$params['end_time']]);
        }

        $data = $query->count();
        return $data;
    }

    /*
     * 取成交额
     */
    public static function getSuccessOrderMoney($params){
        $query = Order::find();
        $query->andWhere(['status'=>'success']);
        if(isset($params['user_id']) && !empty($params['user_id'])) {
            $query->andWhere(['user_id' => $params['user_id']]);
        }
        if(isset($params['begin_time']) && !empty($params['begin_time'])){
            $query->andWhere(['>=','create_time',$params['begin_time']]);
        }
        if(isset($params['end_time']) && !empty($params['end_time'])){
            $query->andWhere(['<=','end_time',$params['end_time']]);
        }

        $data = $query->sum('sum_price');
        return $data;
    }

    public function getBuyer(){
        return $this->hasOne(Buyer::className(),['id'=>'buyer_id']);
    }

    public function getStock(){
        return $this->hasOne(Stock::className(),['id'=>'stock_id']);
    }

    public function getSku(){
        return $this->hasMany(StockAmount::className(),['stock_id'=>'stock_id']);
    }

    public function getCoupon(){
        return $this->hasOne(Coupon::className(),['coupon_id'=>'coupon_id']);
    }

    public function getStockLive(){
        return $this->hasOne(LiveStock::className(),['stock_id'=>'stock_id']);
    }



    /*
     * 订单状态列表
     */
    public static function getOrderStatusList(){
        return [
            'wait_prepay' => '待支付', //订单成生
            'prepayed' => '已支付定金',
            'payed' => '已支付',
            'wait_pay' => '备货完毕',
            'packed' => '商品打包完毕',
            'to_demostic' => '商品海外发出',
            'demostic' => '商品国内入库',
            'to_user' => '已发货',//商品国内发出
            'success' => '订单完成',
            'fail' => '订单取消',
            'full_refund' => '已退款',//订单关闭，已退全款
            'refund' => '订单关闭，已退定金',
            'canceled' => '订单取消',
            'returned' =>'订单关闭，买手已退货',
            'wait_refund' => '备货失败，商品缺货',
            'timeout' => '支付超时',
        ];
    }

}